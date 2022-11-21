<?php

namespace App\Models;

use Storage;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    // 参照させたいSQLのテーブル名を指定
    protected $table = 'files';

    protected $fillable = ['mime', 'storage_path','thumbnail_path', 'filename', 'size', 'disk'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function($model)
        {
            if(file_exists(public_path() . $model->storage_path))
              unlink(public_path() . $model->storage_path);
            if(file_exists(public_path() . $model->thumbnail_path))
                unlink(public_path() . $model->thumbnail_path);
        });
    }
}
