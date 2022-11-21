<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Kyslik\ColumnSortable\Sortable;         //追記 並び替えをcolumn-sortable使って

class Schedule extends Model
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
    protected $table = 'schedules';

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
        'decision_01',
        'decision_02',
        'decision_03',
        'decision_04',
        'decision_05',
        'decision_06',
        'decision_07',
        'decision_08',
        'decision_09',
        'decision_10',
        'decision_11',
        'decision_12'

    ];
}
