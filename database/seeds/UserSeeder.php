<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create
        ([
            'name'     => 'Ahmed Nassag',
            'email'    => 'ahmednabil@yahoo.com',
            'password' => bcrypt('20111993'),
            'roles_name' => ['owner'],
            'status'     => 'مفعل',
        ]);
    }
}
