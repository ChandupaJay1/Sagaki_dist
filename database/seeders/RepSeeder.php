<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reps = [
            [
                'name' => 'Amila Perera',
                'email' => 'rep1@sagaki.com',
                'mobile_number' => '0711234567',
            ],
            [
                'name' => 'Kasun Silva',
                'email' => 'rep2@sagaki.com',
                'mobile_number' => '0772345678',
            ],
            [
                'name' => 'Nuwan Fernando',
                'email' => 'rep3@sagaki.com',
                'mobile_number' => '0783456789',
            ],
            [
                'name' => 'Dinuka Rajapaksha',
                'email' => 'rep4@sagaki.com',
                'mobile_number' => '0754567890',
            ],
            [
                'name' => 'Sahan Jayasinghe',
                'email' => 'rep5@sagaki.com',
                'mobile_number' => '0765678901',
            ],
            [
                'name' => 'Chamara Weerasinghe',
                'email' => 'rep6@sagaki.com',
                'mobile_number' => '0726789012',
            ],
            [
                'name' => 'Lahiru Dissanayake',
                'email' => 'rep7@sagaki.com',
                'mobile_number' => '0707890123',
            ],
            [
                'name' => 'Supun Bandara',
                'email' => 'rep8@sagaki.com',
                'mobile_number' => '0748901234',
            ],
            [
                'name' => 'Gayantha Gunawardena',
                'email' => 'rep9@sagaki.com',
                'mobile_number' => '0719012345',
            ],
            [
                'name' => 'Tharindu Senanayake',
                'email' => 'rep10@sagaki.com',
                'mobile_number' => '0770123456',
            ],
        ];

        foreach ($reps as $rep) {
            $user = User::where('email', $rep['email'])->first();
            if (!$user) {
                User::create([
                    'name' => $rep['name'],
                    'email' => $rep['email'],
                    'mobile_number' => $rep['mobile_number'],
                    'password' => Hash::make('password'),
                    'role' => 'ref',
                    'is_active' => true,
                    'serial_number' => User::generateSerialNumber(), 
                ]);
            }
        }
    }
}
