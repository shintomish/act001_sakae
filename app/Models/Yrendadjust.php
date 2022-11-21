<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Kyslik\ColumnSortable\Sortable;         //追記 並び替えをcolumn-sortable使って

class Yrendadjust extends Model
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
    protected $table = 'yrendadjusts';

    // 追記(ソートに使うカラムを指定
    public $sortable = [
        'id',
    	'announce_at',	// アナウンス
    	'docinfor_at',	// 書類案内日
    	'doccolle_at',	// 資料回収日
        'rrequest',		// 資料再請求日
        'matecret_at',	// 資料作成日
    ];
     public $sortableAs = ['business_name','business_code'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'custm_id',
        'year',
        'absence_flg',
        'trustees_no',
        'communica_flg',
        'announce_at',
        'docinfor_at',
        'doccolle_at',
        'rrequest_at',
        'matecret_at',
        'salary_flg',
        'remark_1',
        'remark_2',
        'cooperat',
        'refund_flg',
        'declaration_flg',
        'annual_flg',
        'withhold_flg',
        'claim_flg',
        'payment_flg',
    ];
}
