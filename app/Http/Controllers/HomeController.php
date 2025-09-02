<?php

namespace App\Http\Controllers;

use App\Events\CreateTicket;
use App\Events\CreateTicketFrontend;
use App\Events\TicketReply;
use App\Events\VerifyReCaptchaToken;
use App\Models\Category;
use App\Models\Conversion;
use App\Models\CustomField;
use App\Models\Faq;
use App\Mail\SendTicket;
use App\Models\UserCatgory;
use App\Mail\SendTicketAdmin;
use App\Mail\SendTicketReply;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Knowledge;
use App\Models\Knowledgebasecategory;
use App\Models\Languages;
use App\Models\Utility;
use App\Models\Settings;
use App\Models\Priority;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Pusher\Pusher;
use Exception;

class HomeController extends Controller
{
    private $language;
    public function __construct()
    {
        // $this->middleware('2fa');

        if (!file_exists(storage_path() . "/installed")) {
            return redirect('install');
        }
        if (moduleIsActive('CustomerLogin')) {
            $this->middleware('CustomerLogin')->only(['index']);
        }

        $language = getActiveLanguage();
        App::setLocale(isset($language) ? $language : 'en');
    }
    public function index()
    {

        $this->middleware('2fa');

        if (!file_exists(storage_path() . "/installed")) {
            return redirect('install');
        }

        $customFields = CustomField::orderBy('order')->get();
        $categories = Category::get();
        $categoryTree = buildCategoryTree($categories);
        $priorities = Priority::get();

        $settings = getCompanyAllSettings();
        $language = isset($settings['default_language']) ? $settings['default_language'] : 'en';
        Session::put('default_language', $language);
        $ticket = null;
        return view('home', compact('categoryTree', 'customFields', 'settings', 'priorities', 'ticket'));
    }


    public function search($lang = '')
    {
        $settings = getCompanyAllSettings();
        if ($lang == '') {
            $lang = getActiveLanguage();
        } else {
            $lang = array_key_exists($lang, languages()) ? $lang : 'en';
        }
        $language = Languages::where('code', $lang)->first();
        App::setLocale($lang);
        return view('search', compact('settings', 'lang', 'language'));
    }

    public function faq()
    {
        $settings = getCompanyAllSettings();
        if ($settings['faq'] == 'on') {
            $faqs = Faq::get();
            return view('faq', compact('faqs', 'settings'));
        } else {
            return redirect('/');
        }
    }

    public function ticketSearch(Request $request)
    {
        $validation = [
            'ticket_id' => ['required'],
            'email' => ['required'],
        ];

        $this->validate($request, $validation);
        if (moduleIsActive('TicketNumber')) {
            $settings = getCompanyAllSettings();
            $ticketPrefix = $settings["ticket_number_prefix"] ?? ''; //remove the prefix
            $ticketId = str_replace($ticketPrefix, '', $request->ticket_id);
            $ticketId = ltrim($ticketId, '0');
            $ticket_id = Ticket::where('id', $ticketId)->where('email', $request->email)->first();
            if (!$ticket_id) {
                return redirect()->back()->with('info', __('Ticket not found'));
            }
            $ticket = Ticket::where('ticket_id', '=', $ticket_id->ticket_id)->where('email', '=', $ticket_id->email)->first();
        } else {
            $ticket = Ticket::where('ticket_id', '=', $request->ticket_id)->where('email', '=', $request->email)->first();
        }
        if ($ticket) {
            return redirect()->route('home.view', Crypt::encrypt($ticket->ticket_id));
        } else {
            return redirect()->back()->with('info', __('Invalid Ticket Number'));
        }

        return view('search');
    }


    public function store(Request $request)
    {
        $settings = getCompanyAllSettings();
        if ($request->type == 'Ticket') {
            $validation = [
                'name' => 'required',
                'email' => 'required|email',
                'category' => 'required',
                'subject' => 'required',
                'status' => 'required',
                'description' => 'required',
                'priority' => 'required',
            ];

            $validation = [];
            if (isset($settings['RECAPTCHA_MODULE']) && $settings['RECAPTCHA_MODULE'] == 'yes') {
                if ($settings['google_recaptcha_version'] == 'v2-checkbox') {
                    $validation['g-recaptcha-response'] = 'required';
                } elseif ($settings['google_recaptcha_version'] == 'v3') {


                    $re = event(new VerifyReCaptchaToken($request));
                    if (!isset($re[0]['status']) || $re[0]['status'] != true) {
                        $key = 'g-recaptcha-response';
                        $request->merge([$key => null]); // Set the key to null

                        $validation['g-recaptcha-response'] = 'required';
                    }
                } else {
                    $validation = [];
                }
            } else {
                $validation = [];
            }

            // ---------------------------------------------------------------------------------

            $this->validate($request, $validation);

            $ticket = new Ticket();
            $ticket->ticket_id = time();
            $ticket->name = $request->name;
            $ticket->email = $request->email;
            $ticket->category_id = $request->category;
            $ticket->priority = $request->priority;
            $ticket->subject = $request->subject;
            $ticket->status = "New Ticket";
            $ticket->is_ticket_assign_to_agent = "Unassigned";
            $ticket->type = "TicketForm";
            $ticket->description = $request->description;
            $ticket->created_by = 1;
            $data = [];
            if ($request->hasfile('attachments')) {
                $errors = [];
                foreach ($request->file('attachments') as $filekey => $file) {
                    $fileNameWithExt = $file->getClientOriginalName();
                    $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                    $extention = $file->getClientOriginalExtension();
                    $filenameToStore = $fileName . '_' . time() . '.' . $extention;
                    $dir = ('tickets/' . $ticket->ticket_id);
                    $path = multipleFileUpload($file, 'attachments', $filenameToStore, $dir);
                    if ($path['flag'] == 1) {
                        $data[] = $path['url'];
                    } elseif ($path['flag'] == 0) {
                        $errors = __($path['msg']);
                        return redirect()->back()->with('error', __($errors));
                    }
                }
            }
            $ticket->attachments = json_encode($data);
            $ticket->save();

            CustomField::saveData($ticket, $request->customField);
            event(new CreateTicket($ticket, $request));
            //pusher
            manageCreateTicketPusher($ticket);
            $error_msg = '';

            // send Email To The Customer
            sendTicketEmail('Send Mail To Customer', $settings, $ticket, $request, $error_msg);

            //Send Email To The Admin
            sendTicketEmail('Send Mail To Admin', $settings, $ticket, $request, $error_msg);

            return redirect()->back()->with('create_ticket', __('Ticket created successfully') . ' <a href="' . route('home.view', Crypt::encrypt($ticket->ticket_id)) . '" target="_blank"><b>' . __('Your unique ticket link is this.') . '</b></a> ' . ((isset($error_msg)) ? '<br> <span class="text-danger">' . $error_msg . '</span>' : ''));
        } else {
            $validation = [
                'name' => 'required|string|max:255',
                'name' => 'required',
                'email' => 'required|email',
                'subject' => 'required',
                'description' => 'required',
            ];

            $validator = Validator::make($request->all(), $validation);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ]);
            }

            $ticket = new Ticket();
            $ticket->ticket_id = time();
            $ticket->name = $request->name;
            $ticket->email = $request->email;
            $ticket->subject = $request->subject;
            $ticket->status = "New Ticket";
            $ticket->is_ticket_assign_to_agent = "Unassigned";
            $ticket->type = "LiveChat";
            $ticket->description = $request->description;
            $ticket->attachments = json_encode([]);
            $ticket->created_by = 1;
            $ticket->save();

            event(new CreateTicket($ticket, $request));
            $data = [
                'id' => $ticket->id,
                'tikcet_id' => $ticket->ticket_id,
                'name' => $ticket->name,
                'subject' => $ticket->subject,
                'status' => $ticket->status,
                'created_at' => $ticket->created_at->diffForHumans(),
                'latestMessage' => $ticket->latestMessages($ticket->id),
                'unreadMessge' => $ticket->unreadMessge($ticket->id)->count(),
                'type' => $ticket->type,
            ];

            //pusher
            manageCreateTicketPusher($ticket);
            $error_msg = '';

            // send Email To The Customer
            sendTicketEmail('Send Mail To Customer', $settings, $ticket, $request, $error_msg);

            //Send Email To The Admin
            sendTicketEmail('Send Mail To Admin', $settings, $ticket, $request, $error_msg);

            $data['status'] = 'success';
            $data['message'] = __('Ticket Create Successfully');
            return $data;
        }
    }

    public function view($ticket_id)
    {
        try {
            $ticket_id = decrypt($ticket_id);
            $ticket = Ticket::where('ticket_id', '=', $ticket_id)->first();
            $settings = getCompanyAllSettings();

            if ($ticket) {
                return view('show', compact('ticket', 'settings'));
            } else {
                return redirect()->back()->with('error', __('Ticket Not Found.'));
            }
        } catch (\Throwable $th) {
            return redirect()->back();
        }
    }

    public function reply(Request $request, $ticket_id)
    {
        $rules = [
            'reply_description' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $message = $validator->getMessageBag();
            return redirect()->back()->with('error', $message->first())->withInput();
        }

        $ticket_id = decrypt($ticket_id);
        $ticket = Ticket::where('ticket_id', '=', $ticket_id)->first();
        if ($ticket) {
            $summernoteContent = $request->reply_description;
            if (!empty($summernoteContent) || $request->hasfile('reply_attachments')) {
                $conversion = new Conversion();
                $conversion->ticket_id = $ticket->id;
                $conversion->description = $summernoteContent;
                $conversion->sender = 'user';

                if ($request->hasfile('reply_attachments')) {
                    foreach ($request->file('reply_attachments') as $filekey => $file) {
                        $fileNameWithExt = $file->getClientOriginalName();
                        $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                        $extention = $file->getClientOriginalExtension();
                        $filenameToStore = $fileName . '_' . time() . '.' . $extention;
                        $dir = ('tickets/' . $ticket->ticket_id);
                        $path = multipleFileUpload($file, 'reply_attachments', $filenameToStore, $dir);

                        if ($path['flag'] == 1) {
                            $data[] = $path['url'];
                        } elseif ($path['flag'] == 0) {
                            return redirect()->back()->with('error', __($path['msg']));
                        }
                    }
                    $conversion->attachments = json_encode($data);
                }
                $conversion->save();


                if ($ticket) {
                    $ticket->status = 'In Progress';
                    $ticket->update();
                }

                event(new TicketReply($conversion, $request));

                $settings = getCompanyAllSettings();
                // pusher
                manageFrontToAdminPusher($conversion, $ticket);

                $request->merge(['type' => 'frontend']);



                // Send Email To Te Agent
                $error_msg = '';
                sendTicketEmail('Reply Mail To Agent', $settings, $ticket, $request, $error_msg);

                // Send Email To The Admin
                sendTicketEmail('Reply Mail To Admin', $settings, $ticket, $request, $error_msg);

                return redirect()->back()->with('success', __('Reply Added Successfully'));
            } else {
                return redirect()->back()->with('error', __('Please add a description or attachment.'));
            }
        } else {
            return redirect()->back()->with('error', __('Ticket Not Found.'));
        }
    }

    public function knowledge(Request $request)
    {
        $settings = getCompanyAllSettings();
        if (isset($settings['knowledge_base']) && $settings['knowledge_base'] == 'on') {
            $knowledgeBaseCategory = Knowledgebasecategory::with('knowledgebase')->orderBy('id', 'desc')->get();
            $knowledgeBase = Knowledge::with('getCategoryInfo')->get();

            return view('knowledge', compact('knowledgeBaseCategory', 'knowledgeBase', 'settings'));
        } else {
            return redirect('/');
        }
    }

    public function knowledgeDescription(Request $request, $knowledgebaseId)
    {
        try {
            $knowledgebaseId = decrypt($knowledgebaseId);
            $descriptions = knowledge::where('id', $knowledgebaseId)->first();
            if ($descriptions) {
                $settings = getCompanyAllSettings();
                return view('knowledgedesc', compact('descriptions', 'settings'));
            } else {
                return redirect()->route('knowledge')->with('error', 'Knowledgebase Not Found.');
            }
        } catch (Exception $e) {
            return redirect()->back();
        }
    }
}
