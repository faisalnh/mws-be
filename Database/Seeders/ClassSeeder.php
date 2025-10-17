<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua grade_id dari tabel grades
        $grades = DB::table('grades')->pluck('uuid')->toArray();

        // Jika grades kosong, hentikan agar tidak error
        if (empty($grades)) {
            $this->command->warn('⚠️ Tidak ada data di tabel grades. Jalankan GradeSeeder terlebih dahulu.');
            return;
        }

        DB::table('classes')->insert([
            [
                'id' => Str::uuid(),
                'grade_id' => $grades[0],
                'name' => 'Kelas SD A',
                'grade_level' => 'sd',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'grade_id' => $grades[0],
                'name' => 'Kelas SD B',
                'grade_level' => 'sd',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'grade_id' => $grades[1] ?? $grades[0],
                'name' => 'Kelas SMP A',
                'grade_level' => 'smp',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
