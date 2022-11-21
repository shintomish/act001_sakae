<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// 下記を追記 2022/11/13
use Illuminate\Database\Eloquent\SoftDeletes;

class MailAttachment extends Model
{
    use HasFactory;

    // 下記を追記 2022/11/13
    use SoftDeletes;
}
