<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Kyslik\ColumnSortable\Sortable;         //追記 並び替えをcolumn-sortable使って

class Advisorsfee extends Model
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
    protected $table = 'advisorsfees';

    // 追記(ソートに使うカラムを指定
    public $sortable = [
        'id',

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
        'advisor_fee',
        'fee_01',
        'fee_02',
        'fee_03',
        'fee_04',
        'fee_05',
        'fee_06',
        'fee_07',
        'fee_08',
        'fee_09',
        'fee_10',
        'fee_11',
        'fee_12',

    ];
}
