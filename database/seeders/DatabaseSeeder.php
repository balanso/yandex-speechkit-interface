<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run()
	{
		// \App\Models\User::factory(10)->create();
		User::create([
		'name'              => 'Admin',
		'email'             => 'admin@admin.com',
		'email_verified_at' => now(),
		'password'          => Hash::make('passw0rd'),
		// password
		'remember_token'    => Str::random(10),
	]);
    }
}
