<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; //追記
use Illuminate\Support\Facades\Schema; // ⭐️ 追加

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // user
        // `login_flg` int(11) NOT NULL DEFAULT 1  COMMENT '顧客(1):社員(2):所属(3)',
        $loop_login_flg = array(
            '00' => array ( 'no'=> 0,  'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,  'name'=>'顧客', ),
            '02' => array ( 'no'=> 2,  'name'=>'社員', ),
            '03' => array ( 'no'=> 3,  'name'=>'所属', ),
        );
        view()->share('loop_login_flg', $loop_login_flg);

        // `admin_flg` int(11) NOT NULL DEFAULT 1  COMMENT '一般(1):管理者:(2)',
        $loop_admin_flg = array(
            '00' => array ( 'no'=> 0,  'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,  'name'=>'一般', ),
            '02' => array ( 'no'=> 2,  'name'=>'管理', ),
        );
        view()->share('loop_admin_flg', $loop_admin_flg);

        // customer
        // `individual_class`int(11) NOT NULL DEFAULT 1 COMMENT '法人(1):個人事業主(2)',
        $loop_individual_class = array(
            '00' => array ( 'no'=> 0,  'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,  'name'=>'法人', ),
            '02' => array ( 'no'=> 2,  'name'=>'個人事業主', ),
        );
        view()->share('loop_individual_class', $loop_individual_class);

        // `closing_month` int(11) NOT NULL DEFAULT 1 COMMENT '法人(1-12)[1月～12月]:個人:確定申告(13),
        // [個人事業主の場合は確定申告が自動入力]',
        $loop_closing_month = array(
            '00' => array ( 'no'=> 0,  'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,  'name'=>'01月', ),
            '02' => array ( 'no'=> 2,  'name'=>'02月', ),
            '03' => array ( 'no'=> 3,  'name'=>'03月', ),
            '04' => array ( 'no'=> 4,  'name'=>'04月', ),
            '05' => array ( 'no'=> 5,  'name'=>'05月', ),
            '06' => array ( 'no'=> 6,  'name'=>'06月', ),
            '07' => array ( 'no'=> 7,  'name'=>'07月', ),
            '08' => array ( 'no'=> 8,  'name'=>'08月', ),
            '09' => array ( 'no'=> 9,  'name'=>'09月', ),
            '10' => array ( 'no'=> 10, 'name'=>'10月', ),
            '11' => array ( 'no'=> 11, 'name'=>'11月', ),
            '12' => array ( 'no'=> 12, 'name'=>'12月', ),
            '13' => array ( 'no'=> 13, 'name'=>'確定申告', ),
        );
        view()->share('loop_closing_month', $loop_closing_month);

       // `start_notification` int(11) DEFAULT 1 COMMENT '開始届 1:未提出 2:提出済み',
       $loop_start_notification = array(
            '00' => array ( 'no'=> 0,  'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,  'name'=>'未提出', ),
            '02' => array ( 'no'=> 2,  'name'=>'提出済み', ),
        );
        view()->share('loop_start_notification', $loop_start_notification);

        // `transfer_notification` int(11) NOT NULL COMMENT '異動届 1:必要なし 2:提出済み',
        $loop_transfer_notification = array(
            '00' => array ( 'no'=> 0,  'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,  'name'=>'必要なし', ),
            '02' => array ( 'no'=> 2,  'name'=>'提出済み', ),
        );
        view()->share('loop_transfer_notification', $loop_transfer_notification);

        // `blue_declaration` int(11) DEFAULT 1 COMMENT '青色申告 1:青色 2:白色',
        $loop_blue_declaration = array(
            '00' => array ( 'no'=> 0,  'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,  'name'=>'青色', ),
            '02' => array ( 'no'=> 2,  'name'=>'白色', ),
        );
        view()->share('loop_blue_declaration', $loop_blue_declaration);

        // `special_delivery_date` int(11) DEFAULT 1 COMMENT '納期の特例 1:未提出 2:提出済み',
        $loop_special_delivery_date = array(
            '00' => array ( 'no'=> 0,  'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,  'name'=>'未提出', ),
            '02' => array ( 'no'=> 2,  'name'=>'提出済み', ),
        );
        view()->share('loop_special_delivery_date', $loop_special_delivery_date);

        // `consumption_tax_filing_period` int(11) DEFAULT 3 COMMENT '消費税申告期間 1:１年 2:３か月ごと 3:毎月',
        $loop_consumption_tax_filing_period = array(
            '00' => array ( 'no'=> 0,  'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,  'name'=>'１年', ),
            '02' => array ( 'no'=> 2,  'name'=>'３か月ごと', ),
            '03' => array ( 'no'=> 3,  'name'=>'毎月', ),
        );
        view()->share('loop_consumption_tax_filing_period', $loop_consumption_tax_filing_period);

        // `active_cancel`  int(11) DEFAULT 1 COMMENT 'アクティブ/解約 1:契約 2:SPOT 3:解約',
        $loop_active_cancel = array(
            '00' => array ( 'no'=> 0,  'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,  'name'=>'契約', ),
            '02' => array ( 'no'=> 2,  'name'=>'SPOT', ),
            '03' => array ( 'no'=> 3,  'name'=>'解約', ),
        );
        view()->share('loop_active_cancel', $loop_active_cancel);

        //`interim_payment` int DEFAULT '1' COMMENT '中間納付 [1:1月～12:12月 13:なし] [決算月の+7ケ月]',
        $loop_interim_payment = array(
            '00' => array ( 'no'=> 0,  'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,  'name'=>'01月', ),
            '02' => array ( 'no'=> 2,  'name'=>'02月', ),
            '03' => array ( 'no'=> 3,  'name'=>'03月', ),
            '04' => array ( 'no'=> 4,  'name'=>'04月', ),
            '05' => array ( 'no'=> 5,  'name'=>'05月', ),
            '06' => array ( 'no'=> 6,  'name'=>'06月', ),
            '07' => array ( 'no'=> 7,  'name'=>'07月', ),
            '08' => array ( 'no'=> 8,  'name'=>'08月', ),
            '09' => array ( 'no'=> 9,  'name'=>'09月', ),
            '10' => array ( 'no'=> 10, 'name'=>'10月', ),
            '11' => array ( 'no'=> 11, 'name'=>'11月', ),
            '12' => array ( 'no'=> 12, 'name'=>'12月', ),
            '13' => array ( 'no'=> 13, 'name'=>'―', ),
        );
        view()->share('loop_interim_payment', $loop_interim_payment);

        // `bill_flg` int DEFAULT '1' COMMENT '会計フラグ 1:× 2:○',
        // `adept_flg` int DEFAULT '1' COMMENT '達人フラグ 1:× 2:○',
        // `confirmation_flg` int DEFAULT '1' COMMENT '税理士確認フラグ 1:× 2:○',
        // `report_flg` int DEFAULT '1' COMMENT '申告フラグ 1:× 2:○',
        $loop_circle_cross = array(
            '00' => array ( 'no'=> 0,  'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,  'name'=>'―', ),
            '02' => array ( 'no'=> 2,  'name'=>'○', ),
        );
        view()->share('loop_circle_cross', $loop_circle_cross);

        // `busi_class` int NOT NULL DEFAULT '1' COMMENT '業務区分 1:代理 2:相談',
        $loop_busi_class = array(
            '00' => array ( 'no'=> 0,  'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,  'name'=>'代理', ),
            '02' => array ( 'no'=> 2,  'name'=>'相談', ),
        );
        view()->share('loop_busi_class', $loop_busi_class);

        // `contents_class` int NOT NULL DEFAULT '1' COMMENT '内容（税目等）1～',
        $loop_contents_class = array(
            '00' => array ( 'no'=> 0,   'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,   'name'=>'一般的な税務・経営の相談', ),
            '02' => array ( 'no'=> 2,   'name'=>'異動届（本店・代表者住所変更）', ),
            '03' => array ( 'no'=> 3,   'name'=>'異動届（本店住所変更）', ),
            '04' => array ( 'no'=> 4,   'name'=>'確定申告の勉強会', ),
            '05' => array ( 'no'=> 5,   'name'=>'帰化申請の為の数字を教示', ),
            '06' => array ( 'no'=> 6,   'name'=>'源泉所得税（0円納付）', ),
            '07' => array ( 'no'=> 7,   'name'=>'設立届・青色・給与支払・納期の特例承認申請書', ),
            '08' => array ( 'no'=> 8,   'name'=>'法人設立・設置届出書（支店設置）', ),
            '09' => array ( 'no'=> 9,   'name'=>'法定調書・給与支払報告書', ),
            '10' => array ( 'no'=> 10,  'name'=>'役員報酬相談', ),
            '11' => array ( 'no'=> 11,  'name'=>'法人税・消費税確定申告', ),
            '12' => array ( 'no'=> 12,  'name'=>'法人税確定申告', ),
            '13' => array ( 'no'=> 13,  'name'=>'消費税申告', ),
            '14' => array ( 'no'=> 14,  'name'=>'確定申告書', ),
            '15' => array ( 'no'=> 15,  'name'=>'確定申告書（訂正申告）', ),
            '16' => array ( 'no'=> 16,  'name'=>'確定申告書・消費税申告書', ),
            '17' => array ( 'no'=> 17,  'name'=>'給与支払・納期の特例承認申請書', ),
            '18' => array ( 'no'=> 18,  'name'=>'年末調整過納額還付請求', ),
            '19' => array ( 'no'=> 19,  'name'=>'会計処理', ),      // 2022/08/25 Add
            '20' => array ( 'no'=> 20,  'name'=>'その他', )
        );
        view()->share('loop_contents_class', $loop_contents_class);

        // `facts_class` int NOT NULL DEFAULT '1' COMMENT '顛末 1～',
        $loop_facts_class = array(
            '00' => array ( 'no'=> 0,   'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,   'name'=>'申告', ),
            '02' => array ( 'no'=> 2,   'name'=>'相談', ),
            '03' => array ( 'no'=> 3,   'name'=>'勉強会', ),
            '04' => array ( 'no'=> 4,   'name'=>'確定申告書提出', ),
            '05' => array ( 'no'=> 5,   'name'=>'還付請求書提出', ),
            '06' => array ( 'no'=> 6,   'name'=>'届出書・報告書提出', ),
            '07' => array ( 'no'=> 7,   'name'=>'届出書提出', ),
            '08' => array ( 'no'=> 8,   'name'=>'数字の教示', ),
            '09' => array ( 'no'=> 9,   'name'=>'会計処理', ),      // 2022/08/25 Add
            '10' => array ( 'no'=> 10,  'name'=>'その他', ),
        );
        view()->share('loop_facts_class', $loop_facts_class);

        // `attach_doc` int NOT NULL DEFAULT '1' COMMENT '添付書面 1:無 2:有',
        $loop_attach_doc = array(
            '00' => array ( 'no'=> 0,   'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,   'name'=>'無', ),
            '02' => array ( 'no'=> 2,   'name'=>'有', ),
        );
        view()->share('loop_attach_doc', $loop_attach_doc);

        // `notificationl_flg` int NOT NULL DEFAULT '1' COMMENT '通知しない(1):通知する(2)',
        $loop_notificationl_flg = array(
            '00' => array ( 'no'=> 0,   'name'=>'選択してください', ),
            '01' => array ( 'no'=> 2,   'name'=>'通知する', ),
            '02' => array ( 'no'=> 1,   'name'=>'通知しない', ),
        );
        view()->share('loop_notificationl_flg', $loop_notificationl_flg);

        // `absence_flg` int DEFAULT '1' COMMENT '年調の有無 1:無 2:有',
        $loop_absence_flg = array(
            '00' => array ( 'no'=> 0,   'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,   'name'=>'無', ),
            '02' => array ( 'no'=> 2,   'name'=>'有', ),
        );
        view()->share('loop_absence_flg', $loop_absence_flg);

        // `communica_flg` int DEFAULT '1' COMMENT '伝達手段 1:CHAT 2:LINE 3:MAIL 4:TELL',
        $loop_communica_flg = array(
            '00' => array ( 'no'=> 0,   'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,   'name'=>'CHAT', ),
            '02' => array ( 'no'=> 2,   'name'=>'LINE', ),
            '03' => array ( 'no'=> 3,   'name'=>'MAIL', ),
            '04' => array ( 'no'=> 4,   'name'=>'TELL', ),
        );
        view()->share('loop_communica_flg', $loop_communica_flg);

        // `salary_flg` int DEFAULT '1' COMMENT '給与情報 1:未 2:済',
        $loop_salary_flg = array(
            '00' => array ( 'no'=> 0,   'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,   'name'=>'未', ),
            '02' => array ( 'no'=> 2,   'name'=>'済', ),
        );
        view()->share('loop_salary_flg', $loop_salary_flg);

        // `refund_flg` int DEFAULT '1' COMMENT '申請すれば還付あり 1:× 2:○',
        $loop_refund_flg = array(
            '00' => array ( 'no'=> 0,   'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,  'name'=>'―', ),
            '02' => array ( 'no'=> 2,  'name'=>'○', ),
        );
        view()->share('loop_refund_flg', $loop_refund_flg);

        // `declaration_flg` int DEFAULT '1' COMMENT '0円納付申告 1:× 2:○',
        $loop_declaration_flg = array(
            '00' => array ( 'no'=> 0,   'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,  'name'=>'―', ),
            '02' => array ( 'no'=> 2,  'name'=>'○', ),
        );
        view()->share('loop_declaration_flg', $loop_declaration_flg);

        // `annual_flg` int DEFAULT '1' COMMENT '年調申告 1:× 2:○',
        $loop_annual_flg = array(
            '00' => array ( 'no'=> 0,   'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,  'name'=>'―', ),
            '02' => array ( 'no'=> 2,  'name'=>'○', ),
        );
        view()->share('loop_annual_flg', $loop_annual_flg);

        // `withhold_flg` int DEFAULT '1' COMMENT '源泉徴収票 1:× 2:○',
        $loop_withhold_flg = array(
            '00' => array ( 'no'=> 0,   'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,  'name'=>'―', ),
            '02' => array ( 'no'=> 2,  'name'=>'○', ),
        );
        view()->share('loop_withhold_flg', $loop_withhold_flg);

        // `claim_flg` int DEFAULT '1' COMMENT '請求フラグ 1:× 2:○',
        $loop_claim_flg = array(
            '00' => array ( 'no'=> 0,   'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,  'name'=>'―', ),
            '02' => array ( 'no'=> 2,  'name'=>'○', ),
        );
        view()->share('loop_claim_flg', $loop_claim_flg);

        // `payment_flg` int DEFAULT '1' COMMENT '入金確認フラグ 1:× 2:○',
        $loop_payment_flg = array(
            '00' => array ( 'no'=> 0,   'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,  'name'=>'―', ),
            '02' => array ( 'no'=> 2,  'name'=>'○', ),
        );
        view()->share('loop_payment_flg', $loop_payment_flg);

        // `payslip_flg` int(11) DEFAULT 1 COMMENT '納付書作成 1:× 2:○',
        $loop_payslip_flg = array(
            '00' => array ( 'no'=> 0,   'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,  'name'=>'―', ),
            '02' => array ( 'no'=> 2,  'name'=>'○', ),
        );
        view()->share('loop_payslip_flg', $loop_payslip_flg);

        // `chaneg_flg` int(11) DEFAULT 1 COMMENT '役員報酬変更なしあり 1:× 2:○',consumption_tax
        $loop_chaneg_flg = array(
            '00' => array ( 'no'=> 0,   'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,  'name'=>'―', ),
            '02' => array ( 'no'=> 2,  'name'=>'○', ),
        );
        view()->share('loop_chaneg_flg', $loop_chaneg_flg);

        // `consumption_tax` int(11) DEFAULT 1 COMMENT '消費税 1:簡易 2:本則 3:免税',
        $loop_consumption_tax_flg = array(
            '00' => array ( 'no'=> 0,  'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,  'name'=>'本則', ),
            '02' => array ( 'no'=> 2,  'name'=>'簡易', ),
            '03' => array ( 'no'=> 3,  'name'=>'免税', ),
        );
        view()->share('loop_consumption_tax_flg', $loop_consumption_tax_flg);

        // `年,
        $loop_year_flg = array(
            '00' => array ( 'no'=> 0,   'name'=>'選択してください', ),
            '01' => array ( 'no'=> 2022,  'name'=>'2022年', ),
            '02' => array ( 'no'=> 2023,  'name'=>'2023年', ),
            '03' => array ( 'no'=> 2024,  'name'=>'2024年', ),
        );
        view()->share('loop_year_flg', $loop_year_flg);

        // `業種,
        $loop_industry = array(
            '000' => array ( 'no'=>    0,  'name'=>'選択してください', ),
            '100' => array ( 'no'=>  100,  'name'=>'EC物販コンサルティング', ),
            '200' => array ( 'no'=>  200,  'name'=>'ECを活用した卸・小売業', ),
            '300' => array ( 'no'=>  300,  'name'=>'ECを活用した卸・小売業、EC物販コンサルティング', ),
            '400' => array ( 'no'=>  400,  'name'=>'ECを活用した卸・小売業、暗号資産コンサルティング', ),
            '500' => array ( 'no'=>  500,  'name'=>'ECを活用した卸・小売業、教育事業', ),
            '600' => array ( 'no'=>  600,  'name'=>'ECを活用した卸・小売業、マッサージ店運営', ),
            '700' => array ( 'no'=>  700,  'name'=>'ECを活用した卸・小売業、飲食業', ),
            '800' => array ( 'no'=>  800,  'name'=>'アーティストのマネジメント及びエージェント業務', ),
            '900' => array ( 'no'=>  900,  'name'=>'暗号資産コンサルティング', ),
            '1000' => array ( 'no'=> 1000,  'name'=>'飲食業', ),
            '1100' => array ( 'no'=> 1100,  'name'=>'インターネットを活用した企業の広告、宣伝等のマーケティング', ),
            '1200' => array ( 'no'=> 1200,  'name'=>'インターネットを利用した情報提供サービス', ),
            '1300' => array ( 'no'=> 1300,  'name'=>'ウェブサイトの運営及び管理等', ),
            '1400' => array ( 'no'=> 1400,  'name'=>'ウェブサイトの企画、開発、制作、運営及び管理', ),
            '1500' => array ( 'no'=> 1500,  'name'=>'営業代行業', ),
            '1600' => array ( 'no'=> 1600,  'name'=>'仮設足場工事', ),
            '1700' => array ( 'no'=> 1700,  'name'=>'競馬予想業', ),
            '1800' => array ( 'no'=> 1800,  'name'=>'コンサルティング講座、養成講座の開催', ),
            '1900' => array ( 'no'=> 1900,  'name'=>'事務代行', ),
            '2000' => array ( 'no'=> 2000,  'name'=>'車両や複合機などを使った資金調達', ),
            '2100' => array ( 'no'=> 2100,  'name'=>'スポーツクラブの経営及び経営に関するコンサルティング', ),
            '2200' => array ( 'no'=> 2200,  'name'=>'スポーツに関する体力及び技術向上支援', ),
            '2300' => array ( 'no'=> 2300,  'name'=>'セミナーの運営事業', ),
            '2400' => array ( 'no'=> 2400,  'name'=>'セミナーの企画・運営', ),
            '2500' => array ( 'no'=> 2500,  'name'=>'セミナーの企画・運営、買付代行', ),
            '2600' => array ( 'no'=> 2600,  'name'=>'ダンススクール運営', ),
            '2700' => array ( 'no'=> 2700,  'name'=>'とび・土工・コンクリート工事', ),
            '2800' => array ( 'no'=> 2800,  'name'=>'パーソナルジム運営', ),
            '2900' => array ( 'no'=> 2900,  'name'=>'パン・菓子類の製造及び販売', ),
            '3000' => array ( 'no'=> 3000,  'name'=>'販売イベント運営、セールスプロモーション', ),
            '3100' => array ( 'no'=> 3100,  'name'=>'フィットネスジムの運営', ),
            '3200' => array ( 'no'=> 3200,  'name'=>'ﾌﾟﾗｽﾁｯｸ製品等の販売代行', ),
            '3300' => array ( 'no'=> 3300,  'name'=>'防水シール施工', ),
            '3400' => array ( 'no'=> 3400,  'name'=>'ホステス', ),
            '3500' => array ( 'no'=> 3500,  'name'=>'翻訳業', ),
            '3600' => array ( 'no'=> 3600,  'name'=>'音楽スタジオ等の経営', ),
            '3700' => array ( 'no'=> 3700,  'name'=>'音響効果制作', ),
            '3800' => array ( 'no'=> 3800,  'name'=>'経営コンサルティング', ),
            '3900' => array ( 'no'=> 3900,  'name'=>'芸能タレント、音楽家等の育成及びマネージメント', ),
            '4000' => array ( 'no'=> 4000,  'name'=>'芸能タレント、文化人、スポーツ選手等の育成及びマネジメント', ),
            '4100' => array ( 'no'=> 4100,  'name'=>'健康、医療、福祉、介護及び経営に関するコンサルタント業務', ),
            '4200' => array ( 'no'=> 4200,  'name'=>'広告の企画及び広告代理店業務', ),
            '4300' => array ( 'no'=> 4300,  'name'=>'国内外の音楽著作権の取得、開発及び管理', ),
            '4400' => array ( 'no'=> 4400,  'name'=>'自然災害による建物の損害の調査', ),
            '4500' => array ( 'no'=> 4500,  'name'=>'酒類、食料品の販売、卸売及び輸出入', ),
            '4600' => array ( 'no'=> 4600,  'name'=>'住宅の増改築・リフォーム等', ),
            '4700' => array ( 'no'=> 4700,  'name'=>'情報コンテンツの販売', ),
            '4800' => array ( 'no'=> 4800,  'name'=>'新車、中古自動車の販売並びに中古自動車の買取及び輸出入', ),
            '4900' => array ( 'no'=> 4900,  'name'=>'電気工事業', ),
            '5000' => array ( 'no'=> 5000,  'name'=>'電気工事業、電気器具の販売及び修理', ),
            '5100' => array ( 'no'=> 5100,  'name'=>'電子部品輸出入業', ),
            '5200' => array ( 'no'=> 5200,  'name'=>'塗装工事・内装工事', ),
            '5300' => array ( 'no'=> 5300,  'name'=>'動画編集、ウェブデザイン等に関する養成スクールの企画及び運営', ),
            '5400' => array ( 'no'=> 5400,  'name'=>'眉毛サロン等の美容サービス業に関する店舗の経営', ),
        );
        view()->share('loop_industry', $loop_industry);

        // `check_01`  int(11) DEFAULT 1 COMMENT '進捗確認01-12月フラグ 1:― 2:△ 3:○ ',
        $loop_check_flg = array(
            '00' => array ( 'no'=> 0,   'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,  'name'=>'×', ),
            '02' => array ( 'no'=> 2,  'name'=>'△', ),
            '03' => array ( 'no'=> 3,  'name'=>'〇', ),
        );
        view()->share('loop_check_flg', $loop_check_flg);

        // `decision_01`  int(11) DEFAULT 1 COMMENT '進捗決定01月フラグ 1:○ 2:●',
        $loop_decision_flg = array(
            '00' => array ( 'no'=> 0,   'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,  'name'=>'○', ),
            '02' => array ( 'no'=> 2,  'name'=>'●', ),
        );
        view()->share('loop_decision_flg', $loop_decision_flg);

        // `mail_flg`  int(11) DEFAULT 1 COMMENT '申請・郵送フラグ 1:― 2:○',
        $loop_mail_flg = array(
            '00' => array ( 'no'=> 0,   'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,  'name'=>'―', ),
            '02' => array ( 'no'=> 2,  'name'=>'○', ),
        );
        view()->share('loop_mail_flg', $loop_mail_flg);

        // `check_flg`  int(11) DEFAULT 1 COMMENT 'ファイル無し(1):ファイル有り(2)',
        $loop_file_check_flg = array(
            '00' => array ( 'no'=> 0,  'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,  'name'=>'無し', ),
            '02' => array ( 'no'=> 2,  'name'=>'有り', ),
        );
        view()->share('loop_file_check_flg', $loop_file_check_flg);

        // `prime_flg`  int(11) DEFAULT 1 COMMENT '優先順位 低(1):中(2):高(3)',
        $loop_file_prime_flg = array(
            '00' => array ( 'no'=> 0,  'name'=>'選択してください', ),
            '01' => array ( 'no'=> 2,  'name'=>'中', ),
            '02' => array ( 'no'=> 1,  'name'=>'低', ),
            '03' => array ( 'no'=> 3,  'name'=>'高', ),
        );
        view()->share('loop_file_prime_flg', $loop_file_prime_flg);

        // `mail_flg`int(11) NOT NULL DEFAULT 1 COMMENT 'MAIL(1):登録(2)',
        $loop_mail_flg = array(
            '00' => array ( 'no'=> 0,  'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,  'name'=>'MAIL', ),
            '02' => array ( 'no'=> 2,  'name'=>'登録', ),
        );
        view()->share('loop_mail_flg', $loop_mail_flg);

        // `individual_class`int(11) NOT NULL DEFAULT 1 COMMENT '法人(1):個人事業主(2)',
        $loop_individual_mail = array(
            '00' => array ( 'no'=> 0,  'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,  'name'=>'法人', ),
            '02' => array ( 'no'=> 2,  'name'=>'個人', ),
            '03' => array ( 'no'=> 3,  'name'=>'全て', ),
        );
        view()->share('loop_individual_mail', $loop_individual_mail);

        //`interim_payment` int DEFAULT '1' COMMENT '中間納付 [1:1月～12:12月 13:なし] [決算月の+7ケ月]',
        $loop_interim_mail = array(
            '00' => array ( 'no'=> 0,  'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,  'name'=>'01月', ),
            '02' => array ( 'no'=> 2,  'name'=>'02月', ),
            '03' => array ( 'no'=> 3,  'name'=>'03月', ),
            '04' => array ( 'no'=> 4,  'name'=>'04月', ),
            '05' => array ( 'no'=> 5,  'name'=>'05月', ),
            '06' => array ( 'no'=> 6,  'name'=>'06月', ),
            '07' => array ( 'no'=> 7,  'name'=>'07月', ),
            '08' => array ( 'no'=> 8,  'name'=>'08月', ),
            '09' => array ( 'no'=> 9,  'name'=>'09月', ),
            '10' => array ( 'no'=> 10, 'name'=>'10月', ),
            '11' => array ( 'no'=> 11, 'name'=>'11月', ),
            '12' => array ( 'no'=> 12, 'name'=>'12月', ),
            '13' => array ( 'no'=> 13, 'name'=>'全て', ),
        );
        view()->share('loop_interim_mail', $loop_interim_mail);

        // `announce_month`告知月 int(11) NOT NULL DEFAULT 1 COMMENT '(1):ー (2):決算月1ケ月前 (3):決算月1ケ月後 (4):決算月2ケ月後 (5):決算月7ケ月後',
        $loop_announce_month = array(
            '00' => array ( 'no'=> 0,  'name'=>'選択してください', ),
            '01' => array ( 'no'=> 1,  'name'=>'―', ),
            '02' => array ( 'no'=> 2,  'name'=>'決算月1ケ月前', ),
            '03' => array ( 'no'=> 3,  'name'=>'決算月1ケ月後', ),
            '04' => array ( 'no'=> 4,  'name'=>'決算月2ケ月後', ),
            '05' => array ( 'no'=> 5,  'name'=>'決算月7ケ月後', ),
            '06' => array ( 'no'=> 6,  'name'=>'会計未処理', ),
        );
        view()->share('loop_announce_month', $loop_announce_month);

        //
        Paginator::useBootstrap();
    }
}
