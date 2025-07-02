<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Str;
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::firstOrCreate(['name' => 'edit users', 'group' => 'users']);
        Permission::firstOrCreate(['name' => 'delete users', 'group' => 'users']);
        Permission::firstOrCreate(['name' => 'add users', 'group' => 'users']);
        Permission::firstOrCreate(['name' => 'view users', 'group' => 'users']);

        Permission::firstOrCreate(['name' => 'edit permissions', 'group' => 'permissions']);
        Permission::firstOrCreate(['name' => 'delete permissions', 'group' => 'permissions']);
        Permission::firstOrCreate(['name' => 'add permissions', 'group' => 'permissions']);
        Permission::firstOrCreate(['name' => 'view permissions', 'group' => 'permissions']);

        Permission::firstOrCreate(['name' => 'edit roles', 'group' => 'roles']);
        Permission::firstOrCreate(['name' => 'delete roles', 'group' => 'roles']);
        Permission::firstOrCreate(['name' => 'add roles', 'group' => 'roles']);
        Permission::firstOrCreate(['name' => 'view roles', 'group' => 'roles']);

        
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $new_admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $new_admin->assignRole('admin');
    }
}
