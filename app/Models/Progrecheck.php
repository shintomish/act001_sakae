<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Kyslik\ColumnSortable\Sortable;         //追記 並び替えをcolumn-sortable使って

class Progrecheck extends Model
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
    protected $table = 'progrechecks';

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
        'businm_no',
        'check_01',
        'check_02',
        'check_03',
        'check_04',
        'check_05',
        'check_06',
        'check_07',
        'check_08',
        'check_09',
        'check_10',
        'check_11',
        'check_12'

    ];
}
