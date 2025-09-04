<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\User; // غيّرها إلى Customer إذا عندك موديل خاص بالعملاء
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $customers = [
            ["name"=>"أحمد محمود ياسين","email"=>"ahmed.yasin@zimam.sa","mobile_number"=>"966567858119","password"=>"0567858119Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"أمين المرزوقي","email"=>"amin.almarzouqi@zimam.sa","mobile_number"=>"966565430093","password"=>"0565430093Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"أنس الحربي","email"=>"Anas@zimam.sa","mobile_number"=>"966570933080","password"=>"0570933080Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"أيمن فلاتة","email"=>"Ayman.Falatah@zimam.sa","mobile_number"=>"966566689686","password"=>"0566689686Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"بدر ردّاد السعدي","email"=>"badr.alsaadi@zimam.sa","mobile_number"=>null,"password"=>"12345678Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"ثامر عبدالكريم العقيلي","email"=>"thamer@zimam.sa","mobile_number"=>"966530158503","password"=>"0530158503Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"حبيب عمر","email"=>"habib.o@zimam.sa","mobile_number"=>"966593467179","password"=>"0593467179Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"حسن عمر أمين","email"=>"Hassnameen@zimam.sa","mobile_number"=>"966539267753","password"=>"0539267753Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"خالد الحربي","email"=>"khaled.al-harbi@zimam.sa","mobile_number"=>"966559007032","password"=>"0559007032Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"خالد الطبيقي","email"=>"K.Altobiqi@zimam.sa","mobile_number"=>"966559073863","password"=>"0559073863Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"رايد الزهراني","email"=>"R.Zahrani@zimam.sa","mobile_number"=>"966566635016","password"=>"0566635016Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"رعد المحمادي","email"=>"raad@zimam.sa","mobile_number"=>"966503447900","password"=>"0503447900Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"ريفانا حسنين","email"=>"R.Hassanain@zimam.sa","mobile_number"=>"966559791777","password"=>"0559791777Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"ريناد الغامدي","email"=>"Renad.Alghamdi@zimam.sa","mobile_number"=>"966554314832","password"=>"0554314832Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"ريناد مؤمنة","email"=>"renad.moumenah@zimam.sa","mobile_number"=>"966550429212","password"=>"0550429212Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"سعيد  عبدو","email"=>"saeed@zimam.sa","mobile_number"=>"966531279178","password"=>"0531279178Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"سلطان الزهراني","email"=>"sultan.alzahrani@zimam.sa","mobile_number"=>"966553903478","password"=>"0553903478Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"صقر القرني","email"=>"s.alqarni@zimam.sa","mobile_number"=>null,"password"=>"12345678Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"عاطف أبو بكر","email"=>"a.abkar@zimam.sa","mobile_number"=>"966541249651","password"=>"0541249651Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"عبدالله الشنبري","email"=>"a.alshanbari@zimam.sa","mobile_number"=>"966548640248","password"=>"0548640248Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"عبدالله العقيلي","email"=>"abdullah.alaqeeli@zimam.sa","mobile_number"=>"966594359559","password"=>"0594359559Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"عبدالله الوايلي","email"=>"a.alwayili@zimam.sa","mobile_number"=>"966563356876","password"=>"0563356876Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"عبدالملك إسماعيل","email"=>"a.malek@zimam.sa","mobile_number"=>"966551256834","password"=>"0551256834Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"عثمان فيرق","email"=>"othman.fairaq@zimam.sa","mobile_number"=>"966545908134","password"=>"0545908134Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"عطيه الغامدي","email"=>"attyah.aljribi@zimam.sa","mobile_number"=>"966505704786","password"=>"0505704786Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"علي باخشوين","email"=>"ali.bakhashwain@zimam.sa","mobile_number"=>"966544500688","password"=>"0544500688Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"غازي الجوهي","email"=>"Gazi.aljohi@zimam.sa","mobile_number"=>"966555579695","password"=>"0555579695Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"فيصل الزهراني","email"=>"Faisal@zimam.sa","mobile_number"=>"966502790442","password"=>"0502790442Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"لجين الحازمي","email"=>"Lujain.Alhazmi@zimam.sa","mobile_number"=>"966541607175","password"=>"0541607175Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"م. سعود فريج سعود الحربي","email"=>"saud@zimam.sa","mobile_number"=>"966562030099","password"=>"0562030099Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"م. سعود محمد القرني","email"=>"saudalqarni@zimam.sa","mobile_number"=>"966503612597","password"=>"0503612597Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"م. ممدوح عبدالكريم العقيلي","email"=>"Mamdooh@zimam.sa","mobile_number"=>"966592224447","password"=>"0592224447Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"مالك باسلامة","email"=>"malik@zimam.sa","mobile_number"=>null,"password"=>"12345678Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"ماهر مرزا","email"=>"maher.mirza@zimam.sa","mobile_number"=>null,"password"=>"12345678Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"محمد الحسيني","email"=>"m.elhuseyni@zimam.sa","mobile_number"=>"966552184434","password"=>"0552184434Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"محمد العقيل","email"=>"m.alaqil@zimam.sa","mobile_number"=>"966557258088","password"=>"0557258088Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"محمد العمري","email"=>"m.elemary@zimam.sa","mobile_number"=>"966576142368","password"=>"0576142368Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"محمد العوفي","email"=>"m.aloufi@zimam.sa","mobile_number"=>"966566558179","password"=>"0566558179Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"محمد المسعودي","email"=>"m.almasuodi@zimam.sa","mobile_number"=>"966552138662","password"=>"0552138662Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"محمد النزيلي","email"=>"M.alnuzaili@zimam.sa","mobile_number"=>"966583103321","password"=>"0583103321Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"محمد إبراهيم","email"=>"mohamed.ibrahim@zimam.sa","mobile_number"=>"966548600438","password"=>"0548600438Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"محمد شاهين","email"=>"m.shahin@zimam.sa","mobile_number"=>"966564195653","password"=>"0564195653Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"محمد عادل قرنفلة","email"=>"MuhammedAQ@zimam.sa","mobile_number"=>"966542303333","password"=>"0542303333Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"محمود المحمد الحسيني","email"=>"mahmoud.h@zimam.sa","mobile_number"=>"966531318241","password"=>"0531318241Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"محمود عطيه","email"=>"m.attia@zimam.sa","mobile_number"=>"966541986799","password"=>"0541986799Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"نشوان السليماني","email"=>"nashwan.alsulaimani@zimam.sa","mobile_number"=>null,"password"=>"12345678Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
            ["name"=>"هيثم غندور محمد","email"=>"haitham.ghandour@zimam.sa","mobile_number"=>"966542891094","password"=>"0542891094Zz","type"=>"customer","is_enable_login"=>1,"lang"=>"ar"],
        ];


        $admin = User::where('type', 'admin')->first();

        foreach ($customers as $customerData) {
            $existing = User::where('email', $customerData['email'])->first();
            if (!$existing) {
                $user = new User();
                $user->name = $customerData['name'];
                $user->email = $customerData['email'];
                $user->mobile_number = $customerData['mobile_number'];
                $user->password = Hash::make($customerData['password']);
                $user->parent = $admin->id;
                $user->type = 'customer'; 
                $user->is_enable_login = 1;
                $user->lang = $customerData['lang'];
                $user->created_by = $admin ? $admin->id : null;
                $user->save();
                
                $customerRole = Role::where('name', 'customer')->first();
                if ($customerRole) { 
                    $user->addRole($customerRole);  
                }
            }

        }
    }
}
