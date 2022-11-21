<?php

namespace App\Models;
use App\Http\Controllers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;

    // 参照させたいSQLのテーブル名を指定
    protected $table = 'books';

    protected $fillable = [
        'name',
        'price',
        'info_date',
        'nowyear',
        'nextyear',
    ];
}
