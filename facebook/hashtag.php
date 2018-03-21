<?php
set_time_limit(0);
error_reporting(0);
define('HASHTAG_NAMESPACE', '#4ito_');/* Sửa hashtag tại đây , chữ thường không viết hoa*/
define('LOG_FILE',__DIR__.'/log.txt');//File lưu thông tin các post đã nhắc hashtag để không nhắc lại lần sau
$token = '';//Token của bạn
$idgroup = '1415192401896193'; /* Id Group */
$results = json_decode(file_get_contents('https://graph.facebook.com/v2.9/' .$idgroup
    . '/feed?fields=id,message,created_time,from&limit=1&access_token=' . $token), true); /* Get Data Post*/
$posts=[];

if(isset($results['data'])){
    $posts=$results['data'];

}
if(!is_array($posts)){
    $posts=[];
}
$today = date('Y-m-d');
$alreadyReminded     = file_get_contents(LOG_FILE);
foreach($posts as $post) {
    $post_id      = $post['id'];
    $message = $post['message'];
    $time        = $post['created_time'];
    if (strpos($time, $today) !== false) {//Bài viết được tạo trong ngày
        //Kiểm tra hashtag
        if (strpos(strtolower($message), HASHTAG_NAMESPACE) === FALSE) {
            //Kiểm tra đã nhắc chưa
            if (strpos($alreadyReminded, $post_id) === FALSE) {
                /* Send Comment  */
                $comment = 'Bổ sung #hashtag vào bài viết nhé, ' . $post['from']['name'] . '!' . "\n\n" . '';
                file_get_contents('https://graph.facebook.com/' . urlencode($post_id) . '/comments?method=post&message=' . urlencode($comment) . '&access_token=' . $token);
                $logFile = fopen(LOG_FILE, "a");
                fwrite($logFile, $post_id . "\n");
                fclose($logFile);
            } else {
                echo 'Đã nhắc hashtag';
            }
        }else{
            echo 'Bài viết đã có hashtag';
        }

    }
}
