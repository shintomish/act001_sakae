<?php

namespace App\Models;
use App\Http\Controllers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'kana',
        'first_code',
        'last_code',
        'prefecture',
        'city',
        'address',
        'other',
        'phone',
        'email',
        'comment',
    ];
}
