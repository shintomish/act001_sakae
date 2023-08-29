@php
if (!function_exists('replaceUrl')) {
    function replaceUrl($text)
    {
        $texts = explode(PHP_EOL, $text);
        //PHP_EOLは,改行コードをあらわす.改行があれば分割する
        // $pattern = '/^https?:\/\/[^\s 　\\\|`^"\'(){}<>\[\]]*$/'; //正規表現パターン
        $pattern = '/^https?:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+/';

        $replacedTexts = array(); //空の配列を用意

        foreach ($texts as $value) {
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
        }
        return implode(PHP_EOL, $replacedTexts);
        //配列にしたtextを文字列にする
    }
}
$comment = replaceUrl($comment);
@endphp
<p>
    ※当メールは、システムからの配信専用となっております。<br>
    ※配信メールに返信しても届きません。<br><br>
    {{ $name }} <br>
</p>
<p>
    お世話になっております。株式会社アイゼンテスト税理でございます。<br><br>
    【---お知らせ内容---】<br>
    {!! nl2br($comment) !!}<br>
    {{-- {!! (htmlspecialchars($comment ?? '')) !!}<br> --}}
</p>
<p>
    ====================================================<br>
    〒332-0017 埼玉県川口市栄町3-12-11 コスモ川口栄町ビル2F<br>
    　Tel:048-253-3922 Fax:048-271-9355<br>
    　　株式会社アイゼンテスト税理<br>
    　　　新冨 泰明(Yasuaki Shintomi)<br>
    　　　　E-Mail:y-shintomi@aizen-sol.co.jp<br>
    ====================================================
</p>