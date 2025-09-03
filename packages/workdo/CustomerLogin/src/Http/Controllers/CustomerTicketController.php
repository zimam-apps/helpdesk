<?php

namespace Workdo\CustomerLogin\Http\Controllers;

use App\Events\CreateTicket;
use App\Models\Category;
use App\Models\CustomField;
use App\Models\Priority;
use App\Models\Ticket;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Events\VerifyReCaptchaToken;
use App\Models\Languages;
use Illuminate\Support\Facades\Validator;

use Pusher\Pusher;

class CustomerTicketController extends Controller
{
    public function index($lang = '')
    {
        $customFields = CustomField::orderBy('order')->get();
        $categories   = Category::get();
        $categoryTree = buildCategoryTree($categories);
        $priorities = Priority::get();

        $settings      = getCompanyAllSettings(); 
        if ($lang == '') {
            $lang = getActiveLanguage();
        } else {
            $lang = array_key_exists($lang, languages()) ? $lang : 'en';
        }
        $language = Languages::where('code',$lang)->first();
        App::setLocale($lang);
        $ticket = null;
        $user = Auth::check() ? Auth::user() : null; // <--- إضافة المتغير $user

        return view('customer-login::ticket.create', compact('categoryTree', 'customFields', 'settings', 'priorities', 'ticket','language','lang','user'));
    }

    public function create()
    {
        return view('customer-login::create');
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

            $request->validate($validation); 

            $ticket = new Ticket();
            $ticket->ticket_id = time();
            $ticket->name = $request->name;
            $ticket->email = $request->email;
            $ticket->mobile_no = $request->mobile_no;
            $ticket->category_id = $request->category;
            $ticket->priority = $request->priority;
            $ticket->subject = $request->subject;
            $ticket->status = "New Ticket";
            $ticket->is_ticket_assign_to_agent = "Unassigned";
            $ticket->type = "TicketForm";
            $ticket->description = $request->description;
            $ticket->created_by = 1;

            if ($request->hasfile('attachments')) {
                $errors = [];
                foreach ($request->file('attachments') as $filekey => $file) {
                    $fileNameWithExt = $file->getClientOriginalName();
                    $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                    $extention = $file->getClientOriginalExtension();
                    $filenameToStore = $fileName . '_' . time() . '.' . $extention;

                    $dir        = ('tickets/' . $ticket->ticket_id);
                    $path = multipleFileUpload($file, 'attachments', $filenameToStore, $dir);
                    if ($path['flag'] == 1) {
                        $data[] = $path['url'];
                    } elseif ($path['flag'] == 0) {
                        $errors = __($path['msg']);
                        return redirect()->back()->with('error', __($errors));
                    }
                }
                $ticket->attachments = json_encode($data);
            }
            $ticket->save();

            // pusher
            manageCreateTicketPusher($ticket);

            CustomField::saveData($ticket, $request->customField);

            event(new CreateTicket($ticket, $request));

            $error_msg = '';

            // send Email To The Customer
            sendTicketEmail('Send Mail To Customer', $settings, $ticket, $request, $error_msg);

            //Send Email To The Admin
            sendTicketEmail('Send Mail To Admin', $settings, $ticket, $request, $error_msg);

            return redirect()->back()->with('create_ticket', __('Ticket created successfully') . ' <a href="' . route('home.view', Crypt::encrypt($ticket->ticket_id)) . '" target="_blank"><b>' . __('Your unique ticket link is this.') . '</b></a> ' . ((isset($error_msg)) ? '<br> <span class="text-danger">' . $error_msg . '</span>' : ''));
        }
    }

    public function show($id)
    {
        return view('customer-login::show');
    }
    public function edit($id)
    {
        return view('customer-login::edit');
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
