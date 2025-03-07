<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Department;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
        ]);

        $admin = Role::create(['name' => 'admin']);
       

        $viewPermission = Permission::create(['name' => 'view_reports']);
        $editPermission = Permission::create(['name' => 'edit_reports']);

        $admin->permissions()->attach([$viewPermission->id, $editPermission->id]);

        // Add roles to users
        $user = User::where('name','admin')->first();
        $user->roles()->attach($admin);

        // Add departments
        $marketing = Department::create(['name' => 'IT']);
        $sales = Department::create(['name' => 'Sales']);

        $user->departments()->attach([$marketing->id,$sales->id]);

        $user->save();
    }
}
