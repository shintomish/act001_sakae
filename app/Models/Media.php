<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'media';
    protected $fillable = array('user_id', 'customer_id', 'name', 'mime', 'path', 'url', 'size' );

    function user() {
        return $this->belongsTo( 'App\Models\User' );
    }

  /**
   * Convert bytes to more appropriate format e.g. MB,GB..
   * @param int $size
   * @return string
   */
    function humanFileSize() {
        if ($this->size >= 1073741824) {
            $fileSize = round($this->size / 1024 / 1024 / 1024,1) . 'GB';
        } elseif ($this->size >= 1048576) {
            $fileSize = round($this->size / 1024 / 1024,1) . 'MB';
        } elseif($this->size >= 1024) {
            $fileSize = round($this->size / 1024,1) . 'KB';
        } else {
            $fileSize = $this->size . ' bytes';
        }
        return $fileSize;
    }
    function media() {
        return $this->hasMany( 'App\Models\Media', 'user_id' );
    }
}
