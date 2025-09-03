<?php

namespace Workdo\CustomerLogin\Http\Controllers;

use App\Models\Languages;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerLoginController extends Controller
{
    public function index()
    {
        return view('customer-login::index');
    }

    public function showRegistrationForm(Request $request,$lang = '')
    {

        if ($lang == '') {
            $lang = getActiveLanguage();
        } else {
            $lang = array_key_exists($lang, languages()) ? $lang : 'en';
        }
        $language = Languages::where('code',$lang)->first();
        $settings = getCompanyAllSettings();
        App::setLocale($lang);
        

        return view('customer-login::register', compact('lang', 'settings','language'));
    }

    public function create()
    {
        return view('customer-login::create');
    }


    public function store(Request $request)
    {
        
            $request->validate([
                'name'    => 'required|string|max:255',
                'email'   => 'required|string|email|max:255|unique:users',
            ]);
            
            $role = Role::where('name', 'customer')->first();
            $settings  = getCompanyAllSettings();
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = !empty($request->password) ? Hash::make($request->password) : null;
            $user->parent = '1';
            $user->is_enable_login = '1' ;
            $user->type = isset($role) ? $role->name : '';
            $user->lang = isset($settings['default_language']) ? $settings['default_language'] : 'en';
            $user->created_by = '1';
            $user->save();
            if ($role) {
                $user->addRole($role);
            }

            // event(new CreateUser($user,$request));

            // if (isset($settings['New User']) && $settings['New User'] == 1) {
            //     $uArr = [
            //         'email' => $user->email,
            //         'password' => $request->password,
            //     ];
            //     $resp = Utility::sendEmailTemplate('New User', [$user->id => $user->email], $uArr);
            // }

            return redirect()->route('login')->with('create_user', __('Customer Registration successfully!') );
         
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
