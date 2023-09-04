<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;         //追記 並び替えをcolumn-sortable使って

class Operation extends Model
{
    use SoftDeletes;
    use Sortable;                   //追記

    // 参照させたいSQLのテーブル名を指定
    protected $table = 'operations';

    //追記(ソートに使うカラムを指定
    public $sortable = [
        'id',
        'name',
        'status_flg',
        'login_verified_at',
        'logout_verified_at',
    ];
    public $sortableAs = ['name','business_name'];
    // protected $fillable = [
    //     'name',
    //     'price',
    //     'info_date',
    //     'nowyear',
    //     'nextyear',
    // ];
}