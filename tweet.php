<?php
// 参考にした記事
// tbot開発物語: http://blog.okumin.com/archives/twitter-bot-2
// tmhOAuth: refer http://www.softel.co.jp/blogs/tech/archives/3295

error_reporting(-1);
//error_reporting(0);

require_once '/usr/share/nginx/www/libs/tmhOAuth/tmhOAuth.php'; //sudo apt-get install php5-curl 必要
require_once 'config.php'; // CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET を定義しておく

$image = "/usr/share/nginx/www/140617/test.jpg";
#
# 写真を a.jpg に撮影
$output=shell_exec("raspistill -hf -vf -w 1000 -h 1000 -t 1000 -o $image");
echo "Output = ".$output; // * failed to open vchiq instance と出た時は sudo chmod a+rw /dev/vchiq

# 「tmhOAuth」クラスのインスタンスを作成
$tmhOAuth = new tmhOAuth(array(
			'consumer_key'    => CONSUMER_KEY,
			'consumer_secret' => CONSUMER_SECRET,
			'token'      => ACCESS_TOKEN,
			'secret'     => ACCESS_TOKEN_SECRET));
$code = $tmhOAuth->request('POST', $tmhOAuth->url('1.1/statuses/update_with_media'),
	array(
		'media[]'  => "@{$image}",
		'status'   => "さつまいも水耕栽培 by 水草エアレーション"
	),
	true,
	true
);
if ($code == 200) {
	echo("投稿しました");
} else {
	print_r($tmhOAuth->response['response']);
}
?>