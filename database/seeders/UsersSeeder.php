<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Morilog\Jalali\Jalalian;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'=> 'test',
            'hijri_date'=> str_replace("/", "-", Jalalian::now()->toDateString()),
            'password'=> Hash::make('123')
        ]);
    }
}
