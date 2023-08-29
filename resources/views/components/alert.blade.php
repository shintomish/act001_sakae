<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notification</title>
</head>
<body>
    @php
    use Illuminate\Support\Facades\Log;
        if (!function_exists('replaceUrl')) {
            function replaceUrl($text)
            {
                $texts = explode(PHP_EOL, $text);
                //PHP_EOLは,改行コードをあらわす.改行があれば分割する
                // $pattern = '/^https?:\/\/[^\s 　\\\|`^"\'(){}<>\[\]]*$/'; //正規表現パターン
                // $pattern = '/^https?:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+/';
                $pattern = '|https*?\://[-_.!~*a-zA-Z0-9;/?:@&=+$,%#]+|';

                $replacedTexts = array(); //空の配列を用意

                $i = 0;
                foreach ($texts as $i => $value) {

                    $replace = preg_replace_callback($pattern, function ($matches) {

                        //textが１行ごとに正規表現にmatchするか確認する
                        if (isset($matches[1])) {
                            return $matches[0]; //$matches[0] がマッチした全体を表す
                        }
                        //既にリンク化してあれば置換は必要ないので、配列に代入
                        return '<a href="' . $matches[0] . '" target="_blank" rel="noopener">' . $matches[0] . '</a>';
                        }, $value);

                    $replacedTexts[] = $replace;
                    //リンク化したコードを配列に代入
                    $i++;
                }
                // Log::debug('topclient show_alert $replacedTexts  = ' . print_r($replacedTexts ,true));
                return implode(PHP_EOL, $replacedTexts);
                //配列にしたtextを文字列にする
            }
        }
        $textarea = replaceUrl($comment);
    @endphp
    {!! nl2br($textarea) !!}
</body>
</html>