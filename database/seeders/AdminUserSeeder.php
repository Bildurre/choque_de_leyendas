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
      'name' => 'Admin',
      'email' => 'admin@admin.admin',
      'password' => Hash::make('admin'),
      'is_admin' => true,
    ]);
    User::create([
      'name' => 'User',
      'email' => 'user@user.user',
      'password' => Hash::make('user'),
      'is_admin' => false,
    ]);
  }
}