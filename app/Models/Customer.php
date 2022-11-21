<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Kyslik\ColumnSortable\Sortable;         //追記 並び替えをcolumn-sortable使って

class Customer extends Model
{
    use HasFactory, Notifiable;
    use Sortable;                   //追記

    // public $samples;
    // public function __construct($value)
    // {
    //     // 親コンストラクタを呼び出す
    //     parent::__construct();
    //     $this->sampleValue = $value;
    // }

    // 参照させたいSQLのテーブル名を指定
    protected $table = 'customers';

    // 追記(ソートに使うカラムを指定
    public $sortable = [
        'business_code',
        'business_name',
		'closing_month',
		'individual_class',
        'represent_name',
        'active_cancel',
        'notificationl_flg',
        'final_accounting_at',
        'corporate_number',
        'email',
        'updated_at'
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'business_code',
        'business_name',
        'closing_month',
        'individual_class',
        'represent_name',
        'industry',
        'email',
        // 入れるとTableのデフォルトがNullになる flgは大丈夫
        // 'prev_sales',
        // 'prev_profit',
        // 'advisor_fee',
        'business_zipcode',
        'business_address',
        'business_tell',
        'represent_zipcode',
        'represent_address',
        'represent_tell',
        'tax_office',
        'start_notification',
        'transfer_notification',
        'blue_declaration',
        'special_delivery_date',
        'interim_payment',
        'consumption_tax',
        'consumption_tax_filing_period',
        'active_cancel',
        'notificationl_flg',
        'referral_destination',
        'final_accounting_at',
        'corporate_number',
        'memo_1',
    ];

    /**
     * CSVヘッダ項目の定義値があれば定義配列のkeyを返す
     *
     * @param string $header
     * @param string $encoding
     * @return string|null
     */
    public static function retrieveCustomerColumnsByValue(string $header ,string $encoding)
    {
        // CSVヘッダとテーブルのカラムを関連付けておく
        $list = [

            'business_code'					=> "事業者コード",
            'business_kana'					=> "事業者名フリガナ",
            'business_name'					=> "事業者名",
            'closing_month'					=> "事業者法人個人区分",
            'blue_declaration'			 	=> "事業者青白区分",
            'business_zipcode'			 	=> "事業者郵便番号1",
            'business_zipcode2'			 	=> "事業者郵便番号2",
            'no_00'							=> "事業者所在地フリガナ",
            'business_address'			 	=> "事業者所在地",
            'business_tel'			 		=> "事業者電話番号1",
            'business_tel2'			 		=> "事業者電話番号2",
            'business_tel3'			 		=> "事業者電話番号3",
            'no_01'			 => "事業者FAX1",
            'no_02'			 => "事業者FAX2",
            'no_03'			 => "事業者FAX3",
            'no_04'			 => "事業者URL",
            'no_05'			 => "事業者メールアドレス",
            'no_06'			 => "関与開始日",
            'no_07'			 => "関与終了日",
            'no_08'			 => "業務区分(税務代理)",
            'no_09'			 => "業務区分(書類作成)",
            'no_10'			 => "業務区分(税務相談)",
            'no_11'			 => "税理士法第33条の2の書面添付",
            'no_12'			 => "予備1",
            'no_13'			 => "予備2",
            'no_14'			 => "予備3",
            'memo_1'						 => "備考",
            'no_15'			 => "事業者法人番号",
            'no_16'			 => "事業者法人区分",
            'no_17'			 => "事業者普通法人等区分",
            'no_18'			 => "事業者公益法人等区分",
            'no_19'			 => "事業者事業内容",
            'no_20'			 => "事業者屋号フリガナ",
            'no_21'			 => "事業者屋号",
            'no_22'			 => "事業者法人整理番号",
            'tax_office'				 => "事業者法人所轄税務署",
            'individual_class'			 => "決算月",
            'no_23'			 => "法人利用者識別番号",
            'no_24'			 => "法人利用者ID",
            'no_25'			 => "代表者名フリガナ",
            'represent_name'			 => "代表者名",
            'represent_kana'			 => "代表者役職",
            'represent_zipcode'			 => "代表者郵便番号1",
            'represent_zipcode2'			 => "代表者郵便番号2",
            'represent_address2'			 => "代表者住所フリガナ",
            'represent_address'			 => "代表者住所",
            'represent_tell'			 => "代表者電話番号1",
            'represent_tell2'			 => "代表者電話番号2",
            'represent_tell3'			 => "代表者電話番号3",
            'no_26'			 => "代表者連絡先1",
            'no_27'			 => "代表者連絡先2",
            'no_28'			 => "代表者連絡先3",
            'no_29'			 => "代表者メールアドレス",
            'no_30'			 => "経理責任者名フリガナ",
            'no_31'			 => "経理責任者名",
            'no_32'			 => "経理責任者郵便番号1",
            'no_33'			 => "経理責任者郵便番号2",
            'no_34'			 => "経理責任者住所フリガナ",
            'no_35'			 => "経理責任者住所",
            'no_36'			 => "経理責任者電話番号1",
            'no_37'			 => "経理責任者電話番号2",
            'no_38'			 => "経理責任者電話番号3",
            'no_39'			 => "経理責任者連絡先1",
            'no_40'			 => "経理責任者連絡先2",
            'no_41'			 => "経理責任者連絡先3",
            'no_42'			 => "経理責任者メールアドレス",
            'no_43'			 => "事業者性別",
            'no_44'			 => "事業者生年月日",
            'no_45'			 => "事業者職業",
            'no_46'			 => "事業者連絡先1",
            'no_47'			 => "事業者連絡先2",
            'no_48'			 => "事業者連絡先3",
            'no_49'			 => "事業者世帯主の氏名",
            'no_50'			 => "事業者世帯主との続柄",
            'no_51'			 => "事業者個人整理番号",
            'no_52'			 => "事業者個人所轄税務署",
            'no_53'			 => "個人利用者識別番号",
            'no_54'			 => "個人利用者ID",
            'no_55'			 => "事業者屋号･雅号フリガナ",
            'no_56'			 => "事業者屋号･雅号",
            'no_57'			 => "事業所郵便番号1",
            'no_58'			 => "事業所郵便番号2",
            'no_59'			 => "事業所所在地フリガナ",
            'no_60'			 => "事業所所在地",
            'no_61'			 => "事業所電話番号1",
            'no_62'			 => "事業所電話番号2",
            'no_63'			 => "事業所電話番号3",
            'no_64'			 => "アクセス権設定",
            'no_65'			 => "アクセス権設定者",
        ];

        foreach ($list as $key => $value) {
            if ($header === mb_convert_encoding($value, $encoding)) {
                return $key;
            }
        }
        return null;
    }
}
