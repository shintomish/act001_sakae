<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;         //追記 並び替えをcolumn-sortable使って

class Newsrepo extends Model
{
    use SoftDeletes;
    use Sortable;                   //追記
    // 参照させたいSQLのテーブル名を指定
    protected $table = 'newsrepos';

    // 追記(ソートに使うカラムを指定
    public $sortable = [
        'id',
        'created_at',
        'mail_flg',
        'individual_mail',
        'interim_mail',
        'announce_month',
    ];

    protected $fillable = [
        'comment',
        'mail_flg',
        'individual_mail',
        'interim_mail',
        'announce_month',
        'created_at',
    ];
}
