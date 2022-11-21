<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Kyslik\ColumnSortable\Sortable;         //追記 並び替えをcolumn-sortable使って

class Wokprocbook extends Model
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
    protected $table = 'wokprocbooks';

    // 追記(ソートに使うカラムを指定
    public $sortable = [
        'id',
    	'refnumber',
    	'busi_class',
        'contents_class',
        'facts_class',
        'proc_date',
        'attach_doc',
        'filing_date',
        'updated_at'
    ];
     public $sortableAs = ['business_name','business_address','name','login_flg'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'year',
        'refnumber',
    	'busi_class',
        'login_flg',
        'custm_id',
        'contents_class',
        'facts_class',
        'proc_date',
        'attach_doc',
        'filing_date',
        'staff_no',
    ];
}
