<?php

namespace Database\Seeders;

use App\Models\LostReport;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Standalone seeder for R1 (Lost Item Reports).
 * Run with: php artisan db:seed --class=R1LostReportSeeder
 */
class R1LostReportSeeder extends Seeder
{
    public function run(): void
    {
        $student = User::firstOrCreate(
            ['email' => 'student.demo@g.bracu.ac.bd'],
            [
                'name' => 'Demo Student',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'student_id' => '22101042',
                'department' => 'CSE',
                'phone' => '01700000000',
            ]
        );

        LostReport::firstOrCreate(
            ['user_id' => $student->id, 'item_name' => 'Black Lenovo 65W Laptop Charger'],
            [
                'category' => 'electronics',
                'description' => 'USB-C 65W charger with a small white sticker on the adapter brick.',
                'lost_date' => now()->subDays(2)->toDateString(),
                'location' => 'UB2 Building - Room 204',
                'status' => LostReport::STATUS_SEARCHING,
            ]
        );

        LostReport::firstOrCreate(
            ['user_id' => $student->id, 'item_name' => 'Casio fx-991EX Calculator'],
            [
                'category' => 'electronics',
                'description' => 'Black scientific calculator with initials written on the back slide cover.',
                'lost_date' => now()->subDays(5)->toDateString(),
                'location' => 'Library (Level 3)',
                'status' => LostReport::STATUS_MATCHED,
            ]
        );

        LostReport::firstOrCreate(
            ['user_id' => $student->id, 'item_name' => 'Navy Blue Umbrella'],
            [
                'category' => 'accessories',
                'description' => 'Compact foldable umbrella with wooden handle left near the entrance.',
                'lost_date' => now()->subDays(9)->toDateString(),
                'location' => 'Main Cafeteria',
                'status' => LostReport::STATUS_RECOVERED,
            ]
        );
    }
}
