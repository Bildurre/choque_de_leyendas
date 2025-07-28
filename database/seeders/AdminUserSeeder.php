<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
  public function run(): void
  {
    User::create([
      'name' => 'Bildurre',
      'email' => 'bildurre@universoalanda.com',
      'password' => Hash::make('152egUoi971'),
      'is_admin' => true,
    ]);
    // User::create([
    //   'name' => 'User',
    //   'email' => 'user@user.user',
    //   'password' => Hash::make('user'),
    //   'is_admin' => false,
    // ]);
  }
}