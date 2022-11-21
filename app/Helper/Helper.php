<?php
namespace App\Helper;

use Log;
use App\User;
use App\Models\Book;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Helper
{
    public function setInformation($someValue)
    {
        $book = '';
        if(!isset($book['name'])){
            if(!is_array($book)){
                $book = array();
            }
        }

        $book = DB::table('books')
            ->where('id', 1)
            ->update([
                'name' =>  $someValue
            ]);

        return "$someValue";

    }
    public function getInformation()
    {
        // $builder = Book::select(['id', 'name']);
        // $builder = $builder->where('id', '=', 1);
        // $array = $builder->get()->toArray();

        $builder = DB::table('books')->select(['name']);
        $someValue = $builder->where('id', '=', 1)->get()->toArray();

        // var_dump($someValue);
        // return "$someValue";
        return $someValue;

    }

    public function startQueryLog()
    {
          \DB::enableQueryLog();
    }

    public function showQueries()
    {
         dd(\DB::getQueryLog());
    }

    public static function instance()
    {
        return new Helper();
    }
}
