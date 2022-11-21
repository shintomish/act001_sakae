<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Kyslik\ColumnSortable\Sortable;         //追記 並び替えをcolumn-sortable使って

class ControlUser extends Authenticatable
{
    use HasFactory, Notifiable;
    use Sortable;                   //追記


    // 参照させたいSQLのテーブル名を指定
    protected $table = 'controlusers';

    //追記(ソートに使うカラムを指定
    public $sortable = [
        'user_id',
        'customer_id',
    ];
    public $sortableAs = ['users_name','business_name'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'user_id',
        'customer_id',
    ];

}
