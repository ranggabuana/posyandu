<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RolesAndAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create roles
        $roleAdmin = Role::create(['name' => 'admin']);
        $rolePosyandu = Role::create(['name' => 'posyandu']);

        // create demo users
        $admin = User::firstOrCreate(
            ['email' => 'admin@posyandu.com'],
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'nama_lengkap' => 'Administrator Sistem',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole($roleAdmin);
    }
}
