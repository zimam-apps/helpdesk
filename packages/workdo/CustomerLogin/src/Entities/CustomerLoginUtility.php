<?php

namespace Workdo\CustomerLogin\Entities;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerLoginUtility extends Model
{
    use HasFactory;

    protected $fillable = [];

    public static function GivePermissionToRoles($role_id = null, $rolename = null)
    {
        $customer_permissions = [
            // tickets
            'ticket manage',
            'ticket create',
            'ticket edit',
            'ticket show',
            'ticket delete',
            'ticket export',
            'ticket reply',
            'tiketnote store',
            'user profile manage',
        ];


        

        if ($role_id == null) {
            $roles_s = Role::where('name', 'customer')->first();
            if ($roles_s) {
                foreach ($customer_permissions as $key => $permission_s) {
                    $permission = Permission::where('name', $permission_s)->first();
                    if (!$roles_s->hasPermission($permission_s)) {
                        $roles_s->givePermission($permission);
                    }
                }
            } 
           
        }
    }
}
