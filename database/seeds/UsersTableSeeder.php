<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user_roles = Role::where('name','User')->first();
        $admin_roles = Role::where('name','Admin')->first();
        $user = new User();
        $user->name = 'becher';
        $user->email = 'becher@gmail.com';
        $user->password = bcrypt('kamote');
        $user->user_type = 'visitor';
        $user->save();
        $user->roles()->attach($user_roles);

        $admin = new User();
        $admin->name = 'syd';
        $admin->email = 'syd@gmail.com';
        $admin->password = bcrypt('kamotingkahoy');
        $admin->user_type = 'admin';
        $admin->save();
        $admin->roles()->attach($admin_roles);
    }
}



