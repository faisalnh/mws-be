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
            ['name' => 'Ms. Latifah', 'email' => 'latifah@millennia21.id'],
            ['name' => 'Ms. Kholida', 'email' => 'kholida@millennia21.id'],
            ['name' => 'Mr. Aria', 'email' => 'aria@millennia21.id'],
            ['name' => 'Ms. Hana', 'email' => 'hana.fajria@millennia21.id'],
            ['name' => 'Ms. Wina', 'email' => 'wina@millennia21.id'],
            ['name' => 'Ms. Sarah', 'email' => 'sarahyuliana@millennia21.id'],
            ['name' => 'Ms. Hanny', 'email' => 'hanny@millennia21.id'],
            ['name' => 'Pak Dodi', 'email' => 'dodi@millennia21.id'],
            ['name' => 'Pak Faisal', 'email' => 'faisal@millennia21.id'],

            ['email' => 'abu@millennia21.id', 'name' => 'Abu Bakar Ali, S.Sos I'],
            ['email' => 'afiyanti.hardiansari@millennia21.id', 'name' => 'Afiyanti Hardiansari'],
            ['email' => 'alinsuwisto@millennia21.id', 'name' => 'Auliya Hasanatin Suwisto, S.IKom'],
            ['email' => 'aprimaputri@millennia21.id', 'name' => 'Ayunda Primaputri'],
            ['email' => 'belakartika@millennia21.id', 'name' => 'Bela Kartika Sari'],
            ['email' => 'nana@millennia21.id', 'name' => 'Berliana Gustina Siregar'],
            ['email' => 'devi.agriani@millennia21.id', 'name' => 'Devi Agriani, S.Pd.'],
            ['email' => 'diya@millennia21.id', 'name' => 'Diya Pratiwi, S.S'],
            ['email' => 'fransiskaeva@millennia21.id', 'name' => 'Fransiska Evasari, S.Pd'],
            ['email' => 'gundah@millennia21.id', 'name' => 'Gundah Basiswi, S.Pd'],
            ['email' => 'hadi@millennia21.id', 'name' => 'Hadi'],
            ['email' => 'himawan@millennia21.id', 'name' => 'Himawan Rizky Syaputra'],
            ['email' => 'alys@millennia21.id', 'name' => 'Krisalyssa Esna Rehulina Tarigan, S.K.Pm'],
            ['email' => 'maria@millennia21.id', 'name' => 'Maria Rosa Apriliana Jaftoran'],
            ['email' => 'nadiamws@millennia21.id', 'name' => 'Nadia'],
            ['email' => 'nanda@millennia21.id', 'name' => 'Nanda Citra Ryani, S.IP'],
            ['email' => 'nathasya@millennia21.id', 'name' => 'Nathasya Christine Prabowo, S.Si'],
            ['email' => 'novia@millennia21.id', 'name' => 'Novia Syifaputri Ramadhan'],
            ['email' => 'widya@millennia21.id', 'name' => 'Nurul Widyaningtyas Agustin'],
            ['email' => 'pipiet@millennia21.id', 'name' => 'Pipiet Anggreiny, S.TP'],
            ['email' => 'cecil@millennia21.id', 'name' => 'Pricilla Cecil Leander, S.Pd'],
            ['email' => 'putri.fitriyani@millennia21.id', 'name' => 'Putri Fitriyani, S.Pd'],
            ['email' => 'raisa@millennia21.id', 'name' => 'Raisa Ramadhani'],
            ['email' => 'rifqi.satria@millennia21.id', 'name' => 'Rifqi Satria Permana, S.Pd'],
            ['email' => 'risma.angelita@millennia21.id', 'name' => 'Risma Ayu Angelita'],
            ['email' => 'risma.galuh@millennia21.id', 'name' => 'Risma Galuh Pitaloka Fahdin'],
            ['email' => 'rizkinurul@millennia21.id', 'name' => 'Rizki Nurul Hayati'],
            ['email' => 'robby.noer@millennia21.id', 'name' => 'Robby Noer Abjuny'],
            ['email' => 'triayulestari@millennia21.id', 'name' => 'Tri Ayu Lestari'],
            ['email' => 'triafadilla@millennia21.id', 'name' => 'Tria Fadilla'],
            ['email' => 'vickiaprinando@millennia21.id', 'name' => 'Vicki Aprinando'],
            ['email' => 'yohana@millennia21.id', 'name' => 'Yohana Setia Risli'],
            ['email' => 'yosafat@millennia21.id', 'name' => 'Yosafat Imanuel Parlindungan'],
            ['email' => 'oudy@millennia21.id', 'name' => 'Zavier Cloudya Mashareen'],
            ['email' => 'zolla@millennia21.id', 'name' => 'Zolla Firmalia Rossa'],
            ['email' => 'chaca@millennia21.id', 'name' => 'Chantika Nur Febryanti'],
            ['email' => 'sisil@millennia21.id', 'name' => 'Najmi Silmi Mafaza'],
            ['email' => 'nayandra@millennia21.id', 'name' => 'Nayandra Hasan Sudra'],
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


        // ================= SE Teacher =================
        $seTeacherRole = Role::firstOrCreate(
            ['name' => 'SE Teacher', 'guard_name' => $guard],
            ['uuid' => (string) Str::uuid()]
        );

        $seTeacherPermissions = Permission::whereIn('name', [
            'index emotional checkin',
            'get emotional checkin',
            'create emotional checkin',
            'update emotional checkin',
            'delete emotional checkin',
        ])->get();
        $seTeacherRole->givePermissionTo($seTeacherPermissions);

        // Daftar SE Teacher
        $seTeacherData = [
            ['email' => 'dhaffa@millennia21.id', 'name' => 'Alifananda Dhaffa Hanif Musyafa, S.Pd'],
            ['email' => 'almia@millennia21.id', 'name' => 'Almia Ester Kristiyany Sinabang, S.Pd'],
            ['email' => 'anggie@millennia21.id', 'name' => 'Anggie Ayu Setya Pradini, S.Pd'],
            ['email' => 'annisa@millennia21.id', 'name' => 'Annisa Fitri Tanjung'],
            ['email' => 'devilarasati@millennia21.id', 'name' => 'Devi Larasati'],
            ['email' => 'dien@millennia21.id', 'name' => 'Dien Islami'],
            ['email' => 'akbarfadholi98@millennia21.id', 'name' => 'Fadholi Akbar'],
            ['email' => 'fasa@millennia21.id', 'name' => 'Faqiha Salma Achmada S.Psi.'],
            ['email' => 'ferlyna.balqis@millennia21.id', 'name' => 'Ferlyna Balqis'],
            ['email' => 'galen@millennia21.id', 'name' => 'Galen Rasendriya'],
            ['email' => 'iis@millennia21.id', 'name' => 'Iis Asifah'],
            ['email' => 'ikarahayu@millennia21.id', 'name' => 'Ika Rahayu'],
            ['email' => 'kusumawantari@millennia21.id', 'name' => 'Nazmi Kusumawantari'],
            ['email' => 'novan@millennia21.id', 'name' => 'Novan Syaiful Rahman'],
            ['email' => 'prisy@millennia21.id', 'name' => 'Prisy Dewanti'],
            ['email' => 'restia.widiasari@millennia21.id', 'name' => 'Restia Widiasari'],
            ['email' => 'rezarizky@millennia21.id', 'name' => 'Reza Rizky Prayudha'],
            ['email' => 'rike@millennia21.id', 'name' => 'Rike Rahmawati S.Pd'],
            ['email' => 'roma@millennia21.id', 'name' => 'Romasta Oryza Sativa Siagian, S.Pd'],
            ['email' => 'salsabiladhiyaussyifa@millennia21.id', 'name' => 'Salsabila Dhiyaussyifa Laela'],
            ['email' => 'tiastiningrum@millennia21.id', 'name' => 'Tiastiningrum Nugrahanti, S.Pd'],
            ['email' => 'vinka@millennia21.id', 'name' => 'Vinka Erawati, S.Pd'],
        ];

        // Buat user SE Teacher satu per satu
        foreach ($seTeacherData as $teacher) {
            $seTeacherUser = User::firstOrCreate(
                ['email' => $teacher['email']],
                [
                    'name' => $teacher['name'],
                    'password' => bcrypt('password'),
                    'uuid' => (string) Str::uuid(),
                ]
            );

            if (!$seTeacherUser->hasRole('SE Teacher')) {
                $seTeacherUser->assignRole($seTeacherRole);
            }
        }

        $this->command->info('✅ Semua SE Teacher berhasil dibuat dan diberi role SE Teacher.');

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
            'index emotional checkin',
            'get emotional checkin',
            'create emotional checkin',
            'update emotional checkin',
            'delete emotional checkin',
        ])->get();
        $staffRole->givePermissionTo($staffPermissions);

        // Daftar staff
        $staffData = [
            ['email' => 'adibah.hana@millennia21.id', 'name' => 'Adibah Hana Widjaya'],
            ['email' => 'wina@millennia21.id', 'name' => 'Azalia Magdalena Septianti Tambunan'],
            ['email' => 'derry@millennia21.id', 'name' => 'Derry Parmanto, S.S'],
            ['email' => 'aya@millennia21.id', 'name' => 'Farhah Alya Nabilah'],
            ['email' => 'jo@millennia21.id', 'name' => 'Fayza Julia Pramesti Hapsari Prayoga'],
            ['email' => 'maulida.yunita@millennia21.id', 'name' => 'Maulida Yunita'],
            ['email' => 'made@millennia21.id', 'name' => 'Ni Made Ayu Juwitasari'],
            ['email' => 'novi@millennia21.id', 'name' => 'Novia Anggraeni'],
            ['email' => 'ismail@millennia21.id', 'name' => 'Nur Muhamad Ismail'],
            ['email' => 'ratna@millennia21.id', 'name' => 'Ratna Merlangen'],
            ['email' => 'rain@millennia21.id', 'name' => 'Shahrani Fatimah Azzahrah'],
            ['email' => 'susantika@millennia21.id', 'name' => 'Susantika Nilasari'],
            ['email' => 'hanny@millennia21.id', 'name' => 'Tien Hadiningsih, S.S'],
            ['email' => 'ari.wibowo@millennia21.id', 'name' => 'Ari Wibowo'],
            ['email' => 'sayed.jilliyan@millennia21.id', 'name' => 'Sayed Jilliyan'],
            ['email' => 'kiki@millennia21.id', 'name' => 'Rizki Amalia Fatikhah'],
            ['email' => 'ian.ahmad@millennia21.id', 'name' => 'Ian Ahmad Fauzi'],
            ['email' => 'andre@millennia21.id', 'name' => 'Andrean Hadinata'],
        ];

        // Buat user Staff satu per satu
        foreach ($staffData as $staff) {
            $staffUser = User::firstOrCreate(
                ['email' => $staff['email']],
                [
                    'name' => $staff['name'],
                    'password' => bcrypt('password'),
                    'uuid' => (string) Str::uuid(),
                ]
            );

            if (!$staffUser->hasRole('Staff')) {
                $staffUser->assignRole($staffRole);
            }
        }

        // ================= Support Staff =================
        $supportStaffRole = Role::firstOrCreate(
            ['name' => 'Support Staff', 'guard_name' => $guard],
            ['uuid' => (string) Str::uuid()]
        );

        $supportStaffPermissions = Permission::whereIn('name', [
            'index emotional checkin',
            'get emotional checkin',
            'create emotional checkin',
            'update emotional checkin',
            'delete emotional checkin',
        ])->get();
        $supportStaffRole->givePermissionTo($supportStaffPermissions);

        // Daftar Support Staff
        $supportStaffData = [
            ['email' => 'abdul.mansyur@millennia21.id', 'name' => 'Abdul Mansyur'],
            ['email' => 'abdullah@millennia21.id', 'name' => 'Abdullah, SE, MM'],
            ['email' => 'adiya.herisa@millennia21.id', 'name' => 'Adiya Herisa'],
            ['email' => 'dina@millennia21.id', 'name' => 'Dina'],
            ['email' => 'dona@millennia21.id', 'name' => 'Dona'],
            ['email' => 'irawan@millennia21.id', 'name' => 'Irawan'],
            ['email' => 'khairul@millennia21.id', 'name' => 'Irul'],
            ['email' => 'sandi@millennia21.id', 'name' => 'Kurnia Sandi'],
            ['email' => 'fathan.qalbi@millennia21.id', 'name' => 'Muhammad Fathan Qorib'],
            ['email' => 'awal@millennia21.id', 'name' => 'Muhammad Gibran Al Wali'],
            ['email' => 'ananta@millennia21.id', 'name' => 'Muhammad Rayhan Ananta'],
            ['email' => 'mukron@millennia21.id', 'name' => 'Mukron'],
            ['email' => 'nopi@millennia21.id', 'name' => 'Nopi Puji Astuti'],
            ['email' => 'robby@millennia21.id', 'name' => 'Robby Anggara'],
            ['email' => 'robiatul@millennia21.id', 'name' => 'Robiatul Adawiah'],
            ['email' => 'rohmatulloh@millennia21.id', 'name' => 'Rohmatulloh'],
            ['email' => 'udom@millennia21.id', 'name' => 'Udom Anatapong'],
            ['email' => 'usep@millennia21.id', 'name' => 'Usep Saefurohman'],
            ['email' => 'yeti@millennia21.id', 'name' => 'Yeti'],
            ['email' => 'danu@millennia21.id', 'name' => 'Danu Irwansyah'],
        ];

        // Buat user Support Staff satu per satu
        foreach ($supportStaffData as $staff) {
            $supportStaffUser = User::firstOrCreate(
                ['email' => $staff['email']],
                [
                    'name' => $staff['name'],
                    'password' => bcrypt('password'),
                    'uuid' => (string) Str::uuid(),
                ]
            );

            if (!$supportStaffUser->hasRole('Support Staff')) {
                $supportStaffUser->assignRole($supportStaffRole);
            }
        }

        $this->command->info('✅ Semua Support Staff berhasil dibuat dan diberi role Support Staff.');



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
            ['email' => 'mahrukh@millennia21.id'],
            [
                'name' => 'Ms. Mahrukh',
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

        // ================= Head Unit SD =================
        $headUnitSDRole = Role::firstOrCreate(
            ['name' => 'Head Unit SD', 'guard_name' => $guard],
            ['uuid' => (string) Str::uuid()]
        );

        $headUnitSDPermissions = Permission::whereIn('name', [
            'index emotional checkin',
            'get emotional checkin',
        ])->get();
        $headUnitSDRole->givePermissionTo($headUnitSDPermissions);

        $headUnitSDUser = User::firstOrCreate(
            ['email' => 'kholida@millennia21.id'],
            [
                'name' => 'Ms. Kholida',
                'password' => bcrypt('password'),
                'uuid' => (string) Str::uuid(),
            ]
        );
        if (!$headUnitSDUser->hasRole('Head Unit SD')) {
            $headUnitSDUser->assignRole($headUnitSDRole);
        }


        // ================= Head Unit JH =================
        $headUnitJHRole = Role::firstOrCreate(
            ['name' => 'Head Unit JH', 'guard_name' => $guard],
            ['uuid' => (string) Str::uuid()]
        );

        $headUnitJHPermissions = Permission::whereIn('name', [
            'index emotional checkin',
            'get emotional checkin',
        ])->get();
        $headUnitJHRole->givePermissionTo($headUnitJHPermissions);

        $headUnitJHUser = User::firstOrCreate(
            ['email' => 'aria@millennia21.id'],
            [
                'name' => 'Pak Aria',
                'password' => bcrypt('password'),
                'uuid' => (string) Str::uuid(),
            ]
        );
        if (!$headUnitJHUser->hasRole('Head Unit JH')) {
            $headUnitJHUser->assignRole($headUnitJHRole);
        }


        // ================= Head of Therapist =================
        $headOfTherapistRole = Role::firstOrCreate(
            ['name' => 'Head of Therapist', 'guard_name' => $guard],
            ['uuid' => (string) Str::uuid()]
        );

        $headOfTherapistPermissions = Permission::whereIn('name', [
            'index emotional checkin',
            'get emotional checkin',
        ])->get();
        $headOfTherapistRole->givePermissionTo($headOfTherapistPermissions);

        $headOfTherapistUser = User::firstOrCreate(
            ['email' => 'hana.fajria@millennia21.id'],
            [
                'name' => 'Ms. Hana',
                'password' => bcrypt('password'),
                'uuid' => (string) Str::uuid(),
            ]
        );
        if (!$headOfTherapistUser->hasRole('Head of Therapist')) {
            $headOfTherapistUser->assignRole($headOfTherapistRole);
        }


        // ================= Therapist (dulu SE Teacher) =================
        $therapistRole = Role::firstOrCreate(
            ['name' => 'Therapist', 'guard_name' => $guard],
            ['uuid' => (string) Str::uuid()]
        );

        $therapistPermissions = Permission::whereIn('name', [
            'index emotional checkin',
            'get emotional checkin',
            'create emotional checkin',
            'update emotional checkin',
            'delete emotional checkin',
        ])->get();
        $therapistRole->givePermissionTo($therapistPermissions);

        // Assign semua SE Teacher lama ke role Therapist
        $therapistData = [
            ['email' => 'dhaffa@millennia21.id', 'name' => 'Alifananda Dhaffa Hanif Musyafa, S.Pd'], // JH
            ['email' => 'almia@millennia21.id', 'name' => 'Almia Ester Kristiyany Sinabang, S.Pd'], // SD
            ['email' => 'anggie@millennia21.id', 'name' => 'Anggie Ayu Setya Pradini, S.Pd'],
            ['email' => 'annisa@millennia21.id', 'name' => 'Annisa Fitri Tanjung'],
            ['email' => 'devilarasati@millennia21.id', 'name' => 'Devi Larasati'],
            // ... lanjutkan semua data SE Teacher lama
        ];

        foreach ($therapistData as $therapist) {
            $therapistUser = User::firstOrCreate(
                ['email' => $therapist['email']],
                [
                    'name' => $therapist['name'],
                    'password' => bcrypt('password'),
                    'uuid' => (string) Str::uuid(),
                ]
            );

            if (!$therapistUser->hasRole('Therapist')) {
                $therapistUser->assignRole($therapistRole);
            }
        }

        User::where('email', 'dhaffa@millennia21.id')->update(['supervisor_id' => $headUnitJHUser->id]);
        User::where('email', 'almia@millennia21.id')->update(['supervisor_id' => $headUnitSDUser->id]);
        
        $this->command->info('✅ Semua Therapist berhasil dibuat dan diberi role Therapist.');
    }
    
}
