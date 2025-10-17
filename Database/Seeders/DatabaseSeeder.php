<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Jalankan semua seeder.
     */
    public function run(): void
    {
        $this->call([
            GradeSeeder::class,
            ClassSeeder::class,
            PermissionUserSeeder::class,
        ]);
    }
}
