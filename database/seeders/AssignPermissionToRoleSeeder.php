<?php

namespace Database\Seeders;

use App\Constants\PermissionConstant;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Contracts\Permission;

class AssignPermissionToRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // role admin
        $admin = Role::where('name', 'admin')->first();
        $employee = Role::where('name', 'employee')->first();

        $admin->givePermissionTo('view users');
        $admin->givePermissionTo('create users');
        $admin->givePermissionTo('edit users');
        $admin->givePermissionTo('remove users');

        $admin->givePermissionTo(PermissionConstant::VIEW_COURSE);
        $admin->givePermissionTo(PermissionConstant::CREATE_COURSE);
        $admin->givePermissionTo(PermissionConstant::EDIT_COURSE);
        $admin->givePermissionTo(PermissionConstant::REMOVE_COURSE);
        $employee->givePermissionTo(PermissionConstant::VIEW_COURSE);
        $employee->givePermissionTo(PermissionConstant::EDIT_COURSE);

    }
}
