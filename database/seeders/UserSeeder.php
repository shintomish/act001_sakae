<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // ←これを追加
use Illuminate\Support\Facades\Hash; // ←これを追加

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('users')->delete();

        DB::table('users')->insert([
            [ 'name' =>'systemuser1', 'email' =>'sysuser1@aizen-sol.co.jp', 'email_verified_at' =>date('Y-m-d H:i:s'), 'password' =>Hash::make('sys1234'), 'organization_id' =>0, 'created_at' =>date('Y-m-d H:i:s'), 'updated_at' =>date('Y-m-d H:i:s')],
            [ 'name' =>'systemuser2', 'email' =>'sysuser2@aizen-sol.co.jp', 'email_verified_at' =>date('Y-m-d H:i:s'), 'password' =>Hash::make('sys1234'), 'organization_id' =>0, 'created_at' =>date('Y-m-d H:i:s'), 'updated_at' =>date('Y-m-d H:i:s')],
            [ 'name' =>'systemuser3', 'email' =>'sysuser3@aizen-sol.co.jp', 'email_verified_at' =>date('Y-m-d H:i:s'), 'password' =>Hash::make('sys1234'), 'organization_id' =>0, 'created_at' =>date('Y-m-d H:i:s'), 'updated_at' =>date('Y-m-d H:i:s')],
            [ 'name' =>'YasuakiShintomi', 'email' =>'shintomi.sh@gmail.com', 'email_verified_at' =>date('Y-m-d H:i:s'), 'password' =>Hash::make('user1234'), 'organization_id' =>1, 'created_at' =>date('Y-m-d H:i:s'), 'updated_at' =>date('Y-m-d H:i:s')],
            [ 'name' =>'ユーザー2', 'email' =>'user2@aizen-sol.co.jp', 'email_verified_at' =>date('Y-m-d H:i:s'), 'password' =>Hash::make('user1234'), 'organization_id' =>1, 'created_at' =>date('Y-m-d H:i:s'), 'updated_at' =>date('Y-m-d H:i:s')],
            [ 'name' =>'ユーザー3', 'email' =>'user3@aizen-sol.co.jp', 'email_verified_at' =>date('Y-m-d H:i:s'), 'password' =>Hash::make('user1234'), 'organization_id' =>1, 'created_at' =>date('Y-m-d H:i:s'), 'updated_at' =>date('Y-m-d H:i:s')],
            [ 'name' =>'ユーザー4', 'email' =>'user4@aizen-sol.co.jp', 'email_verified_at' =>date('Y-m-d H:i:s'), 'password' =>Hash::make('user1234'), 'organization_id' =>1, 'created_at' =>date('Y-m-d H:i:s'), 'updated_at' =>date('Y-m-d H:i:s')],
            [ 'name' =>'ユーザー5', 'email' =>'user5@aizen-sol.co.jp', 'email_verified_at' =>date('Y-m-d H:i:s'), 'password' =>Hash::make('user1234'), 'organization_id' =>1, 'created_at' =>date('Y-m-d H:i:s'), 'updated_at' =>date('Y-m-d H:i:s')],
            [ 'name' =>'ユーザー6', 'email' =>'user6@aizen-sol.co.jp', 'email_verified_at' =>date('Y-m-d H:i:s'), 'password' =>Hash::make('user1234'), 'organization_id' =>2, 'created_at' =>date('Y-m-d H:i:s'), 'updated_at' =>date('Y-m-d H:i:s')],
            [ 'name' =>'ユーザー7', 'email' =>'user7@aizen-sol.co.jp', 'email_verified_at' =>date('Y-m-d H:i:s'), 'password' =>Hash::make('user1234'), 'organization_id' =>2, 'created_at' =>date('Y-m-d H:i:s'), 'updated_at' =>date('Y-m-d H:i:s')],
            [ 'name' =>'ユーザー8', 'email' =>'user8@aizen-sol.co.jp', 'email_verified_at' =>date('Y-m-d H:i:s'), 'password' =>Hash::make('user1234'), 'organization_id' =>2, 'created_at' =>date('Y-m-d H:i:s'), 'updated_at' =>date('Y-m-d H:i:s')],
            [ 'name' =>'ユーザー9', 'email' =>'user9@aizen-sol.co.jp', 'email_verified_at' =>date('Y-m-d H:i:s'), 'password' =>Hash::make('user1234'), 'organization_id' =>2, 'created_at' =>date('Y-m-d H:i:s'), 'updated_at' =>date('Y-m-d H:i:s')],
        ]);

    }
}
