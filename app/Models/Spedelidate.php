<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Kyslik\ColumnSortable\Sortable;         //追記 並び替えをcolumn-sortable使って

class Spedelidate extends Model
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
    protected $table = 'spedelidates';

    // 追記(ソートに使うカラムを指定
    public $sortable = [
        'id',

    ];
    public $sortableAs = ['business_name','business_code','closing_month'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'custm_id',
        'year',
        'officecompe',
        'employee',
        'paymenttype',
        'adept_flg',
        'payslip_flg',
        'declaration_flg',
        'paydate_att',
        'checklist',
        'chaneg_flg',
        'after_change',
        'change_time',
        'linkage_pay',
    ];
}
