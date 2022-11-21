<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Kyslik\ColumnSortable\Sortable;         //追記 並び替えをcolumn-sortable使って

class Applestabl extends Model
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
    protected $table = 'applestabls';

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
        'year',
        'companyname',
        'estadetails',
        'delivery_at',
        'mail_flg'

    ];
}
