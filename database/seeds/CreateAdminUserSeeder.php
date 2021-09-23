<?php

use Illuminate\Database\Seeder;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $mgUser = new \App\Model\MgUser();
        $mgUser->user_name = 'xiaodu';
        $mgUser->unicid = 1;
        $mgUser->is_admin = 1;
        $mgUser->password = \Illuminate\Support\Facades\Hash::make('123456');
        $mgUser->save();
    }
}
