<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $roles = ['admin', 'manager', 'employee'];
        foreach($roles as $role) {
            Role::create([
                'name' => $role,
            ]);
        }

//        DB::table('model_has_roles')->insert([
//            'role_id' => 1,
//            'model_id' => 1,
//            'model_type' => 'App\Models\User'
//    ]);


        $adminPermissions = [
            'create_task', 'edit_task', 'delete_task', 'view_specific_task',
            'view_all_tasks', 'restore_task', 'assign_task', 'change_task_status', 'user_promotion', 'user_demotion',
//            'list_employees', 'show_employee_id', 'Add_employee', 'list_managers', 'Add_manager', 'assign_organization',
//            'update_employee', 'delete_employee', 'create', 'update', 'show', 'delete', 'activation',
//            'list_employees', 'Add_employee', 'Add_manager', 'assign_organization', 'delete_employee', 'list_managers',
//            'update_employee', 'show_employee_id', 'show', 'list_membership', 'show_Manager', 'show_employee',
        ];

        $managerPermissions = [
            'create_task', 'edit_task', 'delete_task', 'view_specific_task',
            'view_all_tasks', 'restore_task', 'assign_task', 'change_task_status'
        ];

        $employeePermissions = [
            'change_task_status'
        ];

        foreach ($adminPermissions as $permission){
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }


        $roleAdmin = Role::where('name', 'admin')->first();
        $roleAdmin->givePermissionTo($adminPermissions);

        $admin = User::where('user_type', 1)->first();
        $admin->assignRole($roleAdmin);


        $roleManager = Role::where('name', 'manager')->first();
        $roleManager->givePermissionTo($managerPermissions);

        $manager = User::where('user_type', 2)->first();
        $manager->assignRole($roleManager);


        $roleEmployee = Role::where('name', 'employee')->first();
        $roleEmployee->givePermissionTo($employeePermissions);

        $employee = User::where('user_type', 3)->first();
        $employee->assignRole($roleEmployee);


    }
}
