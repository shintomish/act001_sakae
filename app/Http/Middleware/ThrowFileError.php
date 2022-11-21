<?php
 
namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
 
/**
 * PHP の $_FILES 内のエラーを Laravel を介して読み取って例外として投げる
 * Class ThrowFileError
 * @package App\Http\Middleware
 */
class ThrowFileError
{
    public function handle(Request $request, Closure $next)
    {
        $errors = [];
        // リクエストの中から送られてきたファイルを総ざらい
        foreach ($request->allFiles() as $file) {
            /** @var UploadedFile $file */
            if ($file->getError() !== UPLOAD_ERR_OK) {// UPLOAD_ERR_OK は PHP 組み込み定数
                // もしエラーが存在するのであれば、エラーメッセージをエラーリストの中に格納。
                // ここは用いるプロジェクトのレスポンス形式に応じて変わりやすいです。
                $errors[] = $file->getErrorMessage();
                // webサーバ（nginx, apache等）のエラーと紛らわしくなるデメリットもありますが
                // エラー内容によって HTTP ステータスを分けるのであればここでエラー発見時に適したステータスの例外を投げるのもありです。
                // 一例が↓のコメントアウト部です。
                /*
                $errorMsgMap = [
                    UPLOAD_ERR_INI_SIZE   => Response::HTTP_REQUEST_ENTITY_TOO_LARGE,
                    UPLOAD_ERR_FORM_SIZE  => Response::HTTP_REQUEST_ENTITY_TOO_LARGE,
                    UPLOAD_ERR_PARTIAL    => Response::HTTP_BAD_REQUEST, // アップロードしている途中でリクエストを中断した時に起こるのがほとんど
                    UPLOAD_ERR_NO_FILE    => Response::HTTP_BAD_REQUEST,
                    UPLOAD_ERR_NO_TMP_DIR => Response::HTTP_INTERNAL_SERVER_ERROR,
                    UPLOAD_ERR_CANT_WRITE => Response::HTTP_INTERNAL_SERVER_ERROR,
                    UPLOAD_ERR_EXTENSION  => Response::HTTP_INTERNAL_SERVER_ERROR,
                ];
                throw new HttpException($errorMsgMap[$file->getError()], $file->getErrorMessage());
                */
            }
        }
        if (! empty($errors)) {
            // もしエラーが存在するならばステータス 400 で HTTP レスポンスを返す例外を投げます。
            throw new HttpException(Response::HTTP_BAD_REQUEST, implode("\n", $errors));
        }
 
        // 何事もなければ次の処理へ移動
        return $next($request);
    }
}