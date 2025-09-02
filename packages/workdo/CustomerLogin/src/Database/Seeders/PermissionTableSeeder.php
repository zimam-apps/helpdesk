<?php

namespace Workdo\CustomerLogin\Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Workdo\CustomerLogin\Entities\CustomerLoginUtility;

class PermissionTableSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();
        Artisan::call('cache:clear');
        $module = 'CustomerLogin';

        $permissions  = [
            'customer ticket-create',
        ];

        $admin = User::where('type', 'admin')->first();
        if (!empty($admin)) {
            $role = Role::where('name', 'customer')->where('created_by', $admin->id)->where('guard_name', 'web')->exists();
            if (!$role) {
                $role                   = new Role();
                $role->name             = 'customer';
                $role->guard_name       = 'web';
                $role->module           = $module;
                $role->created_by       = $admin->id;
                $role->save();
            } else {
                $role = Role::where('name', 'customer')->where('module', 'Base')->first();
            }
        }


        foreach ($permissions as $key => $value) {
            $check = Permission::where('name', $value)->where('module', $module)->exists();
            if ($check == false) {
                $permission = Permission::create(
                    [
                        'name' => $value,
                        'guard_name' => 'web',
                        'module' => $module,
                        'created_by' => 1,
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]
                );
                if (!$role->hasPermission($value)) {
                    $role->givePermission($permission);
                }
            }
        }

        CustomerLoginUtility::GivePermissionToRoles();
    }
}
