<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PermissionUserSeeder extends Seeder
{
    public function run(): void
    {
        $guard = 'sanctum';

        // ================= Permissions =================
        $permissions = [
            'index user',
            'get user',
            'create user',
            'update user',
            'delete user',
            'index emotional checkin',
            'get emotional checkin',
            'create emotional checkin',
            'update emotional checkin',
            'delete emotional checkin',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => $guard],
                ['uuid' => (string) Str::uuid()]
            );
        }

        // ================= Admin =================
        $adminRole = Role::firstOrCreate(
            ['name' => 'Admin', 'guard_name' => $guard],
            ['uuid' => (string) Str::uuid()]
        );
        $adminRole->givePermissionTo(Permission::where('guard_name', $guard)->get());

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'uuid' => (string) Str::uuid(),
            ]
        );
        if (!$adminUser->hasRole('Admin')) {
            $adminUser->assignRole($adminRole);
        }

        // ================= Teacher =================
        $teacherRole = Role::firstOrCreate(
            ['name' => 'Teacher', 'guard_name' => $guard],
            ['uuid' => (string) Str::uuid()]
        );

        $teacherPermissions = Permission::whereIn('name', [
            'index emotional checkin',
            'get emotional checkin',
            'create emotional checkin',
            'update emotional checkin',
        ])->get();

        $teacherRole->givePermissionTo($teacherPermissions);

        $teachers = [
            ['name' => 'Ms. Latifah', 'email' => 'latifah.teacher@example.com'],
            ['name' => 'Ms. Kholida', 'email' => 'kholida.teacher@example.com'],
            ['name' => 'Mr. Aria', 'email' => 'aria.teacher@example.com'],
            ['name' => 'Ms. Hana', 'email' => 'hana.teacher@example.com'],
            ['name' => 'Ms. Wina', 'email' => 'wina.teacher@example.com'],
            ['name' => 'Ms. Sarah', 'email' => 'sarah.teacher@example.com'],
            ['name' => 'Ms. Hanny', 'email' => 'hanny.teacher@example.com'],
            ['name' => 'Pak Dodi', 'email' => 'dodi.teacher@example.com'],
            ['name' => 'Pak Faisal', 'email' => 'faisal.teacher@example.com'],
        ];

        foreach ($teachers as $teacher) {
            $user = User::firstOrCreate(
                ['email' => $teacher['email']],
                [
                    'name' => $teacher['name'],
                    'password' => bcrypt('password'),
                    'uuid' => (string) Str::uuid(),
                ]
            );

            if (!$user->hasRole('Teacher')) {
                $user->assignRole($teacherRole);
            }
        }

        $this->command->info('✅ Semua guru berhasil dibuat dan diberi role Teacher.');

        // ================= Student =================
        $studentRole = Role::firstOrCreate(
            ['name' => 'Student', 'guard_name' => $guard],
            ['uuid' => (string) Str::uuid()]
        );

        $studentPermissions = Permission::whereIn('name', [
            'index emotional checkin',
            'get emotional checkin',
            'create emotional checkin',
            'update emotional checkin',
            'delete emotional checkin',
        ])
            ->where('guard_name', $guard)
            ->get();

        $studentRole->givePermissionTo($studentPermissions);

        $allClasses = DB::table('classes')->get();

        foreach ($allClasses as $class) {
            echo "ID: {$class->id}, Name: {$class->name}, Grade Level: {$class->grade_level}\n";
        }

        $dummyClass = $allClasses->firstWhere('name', 'Kelas SD B');

        if (!$dummyClass) {
            $this->command->warn('⚠️ Tidak ada data di tabel classes. Jalankan ClassSeeder terlebih dahulu.');
            return;
        }

        $students = [
            ['name' => 'Nafisa Angelica Qurrota Aini', 'email' => 'nafisa@millennia21.id'],
            ['name' => 'Mikail Rasyefki', 'email' => 'michael@millennia21.id'],
            ['name' => 'Kenisha Azkayra Prabawa', 'email' => 'kenisha@millennia21.id'],
            ['name' => 'Aydira Malaika Ridwansyah', 'email' => 'aydira@millennia21.id'],
            ['name' => 'Prinz Averey Ikhsan', 'email' => 'prinz@millennia21.id'],
            ['name' => 'Putri Athena Mutiksari', 'email' => 'athena@millennia21.id'],
            ['name' => 'Dindi Seraphina', 'email' => 'dindi@millennia21.id'],
            ['name' => 'Gaea Alandra Ardhanny', 'email' => 'gaea.alandra@millennia21.id'],
            ['name' => 'Aralt Cendekia Wicaksono', 'email' => 'aralt.wicaksono@millennia21.id'],
            ['name' => 'Muhammad Rafif Cakradinata', 'email' => 'muhammad.rafif@millennia21.id'],
        ];

        foreach ($students as $student) {
            $studentUser = User::updateOrCreate(
                ['email' => $student['email']],
                [
                    'uuid' => (string) Str::uuid(),
                    'name' => $student['name'],
                    'password' => bcrypt('password123'),
                    'class_id' => $dummyClass->id,
                ]
            );

            if (!$studentUser->hasRole('Student')) {
                $studentUser->assignRole($studentRole);
            }
        }

        $this->command->info('✅ ' . count($students) . ' Student users berhasil dibuat dengan class_id: ' . $dummyClass->id);

        // ================= Parent =================
        $parentRole = Role::firstOrCreate(
            ['name' => 'Parent', 'guard_name' => $guard],
            ['uuid' => (string) Str::uuid()]
        );

        $parentPermissions = Permission::whereIn('name', [
            'index emotional checkin',
            'get emotional checkin',
        ])->get();
        $parentRole->givePermissionTo($parentPermissions);

        $parentUser = User::firstOrCreate(
            ['email' => 'parent@example.com'],
            [
                'name' => 'Parent',
                'password' => bcrypt('password'),
                'uuid' => (string) Str::uuid(),
            ]
        );
        if (!$parentUser->hasRole('Parent')) {
            $parentUser->assignRole($parentRole);
        }

        // ================= Staff =================
        $staffRole = Role::firstOrCreate(
            ['name' => 'Staff', 'guard_name' => $guard],
            ['uuid' => (string) Str::uuid()]
        );

        $staffPermissions = Permission::whereIn('name', [
            'index user',
            'get user',
        ])->get();
        $staffRole->givePermissionTo($staffPermissions);

        $staffUser = User::firstOrCreate(
            ['email' => 'staff@example.com'],
            [
                'name' => 'Staff',
                'password' => bcrypt('password'),
                'uuid' => (string) Str::uuid(),
            ]
        );
        if (!$staffUser->hasRole('Staff')) {
            $staffUser->assignRole($staffRole);
        }

        // ================= Director =================
        $directorRole = Role::firstOrCreate(
            ['name' => 'Director', 'guard_name' => $guard],
            ['uuid' => (string) Str::uuid()]
        );

        $directorPermissions = Permission::whereIn('name', [
            'index user',
            'get user',
            'index emotional checkin',
        ])->get();
        $directorRole->givePermissionTo($directorPermissions);

        $directorUser = User::firstOrCreate(
            ['email' => 'Ms.Mahrukh@example.com'],
            [
                'name' => 'Ms.Mahrukh',
                'password' => bcrypt('password'),
                'uuid' => (string) Str::uuid(),
            ]
        );
        if (!$directorUser->hasRole('Director')) {
            $directorUser->assignRole($directorRole);
        }

        // ================= Headmaster =================
        $headmasterRole = Role::firstOrCreate(
            ['name' => 'Headmaster', 'guard_name' => $guard],
            ['uuid' => (string) Str::uuid()]
        );

        $headmasterPermissions = Permission::whereIn('name', [
            'index user',
            'get user',
            'index emotional checkin',
        ])->get();

        $headmasterRole->givePermissionTo($headmasterPermissions);

        $headmasterUser = User::firstOrCreate(
            ['email' => 'arya.headmaster@example.com'],
            [
                'name' => 'Pak Arya',
                'password' => bcrypt('password'),
                'uuid' => (string) Str::uuid(),
            ]
        );

        if (!$headmasterUser->hasRole('Headmaster')) {
            $headmasterUser->assignRole($headmasterRole);
        }

        $this->command->info('✅ Headmaster (Pak Arya) berhasil dibuat dan diberi role Headmaster.');
    }
}
