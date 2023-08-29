<?php

return [
    '400' => [
      'title'   => '400 Bad Request',
      'message' => 'リクエストにエラーがあります',
      'detail'  => 'このレスポンスは、構文が無効であるためサーバーがリクエストを理解できないことを示します。',
    ],
    '401' => [
      'title'   => '401 Unauthorized',
      'message' => '認証に失敗しました',
      'detail'  => 'リクエストされたリソースを得るために認証が必要です。',
    ],
    '403' => [
      'title'   => '403 Forbidden',
      'message' => 'あなたにはアクセス権がありません',
      'detail'  => 'クライアントがコンテンツへのアクセス権を持たず、サーバーが適切な応答への返信を拒否していることを示します。',
    ],
    '404' => [
      'title'   => '404 Not Found',
      'message' => '該当アドレスのページを見つける事ができませんでした',
      'detail'  => 'サーバーは要求されたリソースを見つけることができなかったことを示します。 URLのタイプミス、もしくはページが移動または削除された可能性があります。 トップページに戻るか、もう一度検索してください。',
    ],
    '500' => [
      'title'   => '500 Internal Server Error',
      'message' => 'サーバー内部でエラーが発生しました',
      'detail'  => 'プログラムに文法エラーがあったり、設定に誤りがあった場合などに返されます。管理者へ連絡してください。',
    ],
    '503' => [
      'title'   => '503 Service Unavailable',
      'message' => 'このページへは事情によりアクセスできません',
      'detail'  => 'サービスが一時的に過負荷やメンテナンスで使用不可能な状態です。',
    ],
];

?>
