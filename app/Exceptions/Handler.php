<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Log;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\ViewErrorBag; // add
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface; // add
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        // Log::info('parent::render START');

        // Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
        // The GET method is not supported for this route. Supported methods: POST.
        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
        //    return response()->view('support.error', ['msg' => "サポート外メソッドです"]);
        }

        // 「the page has expired due to inactivity. please refresh and try again」を表示させない
        if ($exception instanceof TokenMismatchException) {
            Log::info('$exception instanceof TokenMismatchException END');
            return redirect('/login')->with('message', 'セッションの有効期限が切れました。再度ログインしてください。');
        }
        // toastrというキーでメッセージを格納
        session()->flash('toastr', config('toastr.session_warn'));

        // Log::info('parent::render END');
        return parent::render($request, $exception);
    }

    /**
     * Render the given HttpException.
     *
     * @param  \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(HttpExceptionInterface $e)
    {
        $this->registerErrorViewPaths();

        // 「the page has expired due to inactivity. please refresh and try again」を表示させない
        if ($e->getStatusCode() === 419) {
            return redirect('/login')->with('message', 'セッションの有効期限が切れました。再度ログインしてください。');
        }

        if (view()->exists($view = "errors::{$e->getStatusCode()}")) {
            return response()->view($view, [
                'errors' => new ViewErrorBag,
                'exception' => $e,
            ], $e->getStatusCode(), $e->getHeaders());
        }

        return $this->convertExceptionToResponse($e);
    }

}
