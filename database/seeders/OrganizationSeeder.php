<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // ←これを追加
use Illuminate\Support\Facades\Hash; // ←これを追加

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('organizations')->delete();

        DB::table('organizations')->insert([
          [ 'id' => null, 'name' => '株式会社 アイゼン・ソリューション', 'kana' => 'かぶしきがいしゃ あいぜん そりゅーしょん', 'first_code' => '332', 'last_code' => '17', 'prefecture' => '埼玉県', 'city' => '川口市', 'address' => '栄町3-12-11', 'other' => 'コスモ川口栄町2F', 'phone' => '048-271-9355', 'email' => 'info@aizen-sol.co.jp', 'comment' => 'システム管理用', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
          [ 'id' => null, 'name' => 'アルケーエコ株式会社', 'kana' => 'あるけーえこかぶしきがいしゃ', 'first_code' => '110', 'last_code' => '15', 'prefecture' => '東京都', 'city' => '台東区', 'address' => '東上野3-14-7', 'other' => '龍田ビル5階', 'phone' => '03-5826-4368', 'email' => 'n.yabu@arkhe-eco.com', 'comment' => '使用ユーザー', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
          [ 'id' => null, 'name' => '株式会社  EFGH', 'kana' => 'かぶしきがいしゃ あいぜん そりゅーしょん３', 'first_code' => '332', 'last_code' => '17', 'prefecture' => '埼玉県', 'city' => '川口市', 'address' => '栄町3-12-11', 'other' => 'コスモ川口栄町2F', 'phone' => '048-271-9355', 'email' => 'info@aizen-sol.co.jp', 'comment' => 'これはテスト1', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ]);
    }
}
