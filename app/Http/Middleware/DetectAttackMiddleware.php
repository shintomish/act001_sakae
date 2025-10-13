<?php

// 2025/10/10 攻撃ペイロード対応 Log4Shell攻撃解析
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DetectAttackMiddleware
{
    protected array $attackPatterns = [
        '/\${\s*\$\{?jndi/iu',      // Log4Shell 攻撃
        '/ldap:\/\//iu',
        '/<script.*?>/iu',          // XSS
        '/union\s+select/iu',       // SQL Injection
        '/sleep\(\d+\)/iu',
        '/benchmark\s*\(/iu',
    ];

    public function handle($request, Closure $next)
    {
        // Log::info('[DetectAttack] fullUrl:' . $request->fullUrl());
        // Log::info('[DetectAttack] rawContent:' . substr($request->getContent(), 0, 200));
        // Log::info('[DetectAttack] all:' . substr(json_encode($request->all()), 0, 200));

        $input = $request->getContent() . ' ' . $request->fullUrl() . ' ' . json_encode($request->all());
        $detected = false;

        foreach ($this->attackPatterns as $pattern) {
            if (preg_match($pattern, $input)) {
                $detected = true;
                break;
            }
        }

        if ($detected) {

            Log::info('TrustProxies handle called: ' . $request->fullUrl());

            // メール通知
            try {
                Mail::raw("🚨 攻撃検知\n" . print_r($info, true), function ($message) {
                $message->to('y-shintomi@aizen-sol.co.jp')->subject('🚨 Laravel 攻撃検知通知');
                });
            } catch (\Throwable $e) {
                // メール送信失敗しても処理を止めない
            }

            // ここにブロック処理を追加！
            // Log::warning('🚨 Blocked attack from ' . $request->ip() . ' URL: ' . $request->fullUrl());
            Log::warning('🚨 [DetectAttack] Blocked attack from ' . $request->ip() . ' URL hash: ' . hash('sha256', $request->fullUrl()));

            abort(403, 'Access forbidden');
        }

        return $next($request);
    }
}
