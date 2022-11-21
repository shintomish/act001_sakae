<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Kyslik\ColumnSortable\Sortable;         //追記 並び替えをcolumn-sortable使って

class UploadUser extends Model
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

    // 自動更新を無効
    // public $timestamps = false;

    // 参照させたいSQLのテーブル名を指定
    protected $table = 'uploadusers';

    //追記(ソートに使うカラムを指定
    public $sortable = [
        'id',
        'foldername',
        'business_name',
        'yearmonth',
        'check_flg',
        'prime_flg',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'foldername',
        'business_name',
        'yearmonth',
        'organization_id',
        'customer_id',
    ];
}
