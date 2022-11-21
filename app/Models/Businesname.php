<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Kyslik\ColumnSortable\Sortable;         //追記 並び替えをcolumn-sortable使って

class Businesname extends Model
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
    protected $table = 'businesnames';

    // 追記(ソートに使うカラムを指定
    public $sortable = [
        'id',

    ];
     public $sortableAs = ['business_name'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'custm_id',
        'year',
        'businm_01',
        'businm_02',
        'businm_03',
        'businm_04',
        'businm_05',
        'businm_06',
        'businm_07',
        'businm_08',
        'businm_09',
        'businm_10',
        'businm_11',
        'businm_12',
        'businm_13',
        'businm_14',
        'businm_15',
        'businm_16',
        'businm_17',
        'businm_18',
        'businm_19',
        'businm_20'
    ];
}
