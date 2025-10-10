<?php


namespace App\Http\Middleware;

// use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TrustProxies extends \Illuminate\Http\Middleware\TrustProxies
{
    /**
     * The trusted proxies for this application.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies;

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;

    // 2025/10/10 攻撃ペイロード対応 Log4Shell攻撃解析
    public function handle($request, Closure $next)
    {
        // 攻撃パターン検出・チェック対象文字列
        // $attackPatterns = ['${jndi', 'TomcatBypass', 'Base64/ZXhwb3J0', 'ldap://'];
        $attackPatterns = [
            '${jndi', 'jndi:', 'ldap://', 'ldaps://',
            'TomcatBypass', 'Base64/ZXhwb3J0', // 既出ペイロードの一部
            '<script', 'union select', 'sleep(', 'benchmark('
        ];

        // $url = $request->fullUrl();
        // 検査対象をまとめる（URL / GET 全体 / POST body / ヘッダの一部）
        $url = $request->fullUrl()
               . ' ' . json_encode($request->all())
               . ' ' . $request->getContent()
               . ' ' . $request->header('User-Agent', '');

        $detected = false;

        foreach ($attackPatterns as $pattern) {
            if (stripos($url, $pattern) !== false) {
                $detected = true;
                break;
            }
        }

        if ($detected) {
            Log::warning('[DetectAttack] Blocked attack from ' . $request->ip() . ' pattern:' . $pattern . ' URL:' . $request->fullUrl());
            abort(403, 'Access forbidden');
        }

        // return $next($request);
        return parent::handle($request, $next);
    }

}
