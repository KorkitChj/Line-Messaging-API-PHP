<?php
require "vendor/autoload.php";
require "Database.php";


require_once('vendor/linecorp/line-bot-sdk/line-bot-sdk-tiny/LINEBotTiny.php');

$API_URL = 'https://api.line.me/v2/bot/message';

$access_token = 'IQ+wQasFJKoCI8pwPEirOqIDtYEAWuUnHU2AKczJIaYtHGGqizcDfkZpleDLm8KnrPxJluEJ+RRtPuvQ+cpS7MxIx18RfgCRR0buzsrrhfVhhmd+sLAIRZEtZepz0aTcawtJYwXN1RLbEg0XeEyBbgdB04t89/1O/w1cDnyilFU=';
$channelSecret = 'f13d348e9d9659c5a7196ad413cb4428';


$POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

$request = file_get_contents('php://input');   // Get request content
$events = json_decode($request, true);   // Decode JSON to Array


// if ( sizeof($events['events']) > 0 ) {
if (!is_null($events['events'])) {

    foreach ($events['events'] as $event) {
        if ($event['type'] == 'follow') {
            $message_id = $event['message']['id'];
            $reply_message = $event['message']['text'];


            $UserToken = $event['source']['userId'];
            $ReplyToken = $event['replyToken'];

            $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
            $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);
            $response = $bot->getProfile($UserToken);
            if ($response->isSucceeded()) {
                $profile = $response->getJSONDecodedBody();
                $displayName = $profile['displayName'];
                // echo $profile['displayName'];
                // echo $profile['pictureUrl'];
                // echo $profile['statusMessage'];


                $sql = $conn->prepare("INSERT INTO st.user (name,lastname,tokenlineid,lineid) VALUES (?,?,?,?)")->execute(["testline", "testname", $UserToken, $displayName]);
                //$sql = $conn->prepare("UPDATE AccountBackend SET TokenLineId=? WHERE LineId=?")->execute([$UserToken,$displayName]);
            }


            $msg = "Line Name : " . $displayName;

            $data = [
                'replyToken' => $ReplyToken,
                'messages' => [[
                    'type' => 'text',
                    'text' => $msg
                ]]
            ];

            $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);

            $send_result = send_reply_message($API_URL . '/reply', $POST_HEADER, $post_body);
            echo "Result: " . $send_result . "\r\n";



            //  $conn->prepare("INSERT INTO `db_line_userid` (`userid`,`lineid`,`reply_msg`, `date_in`) VALUES ('$user_id','$reply_message','$message_id', now())")->execute();
        } else if ($event['type'] == 'message') {

            $message_id = $event['message']['id'];
            $reply_message = $event['message']['text'];


            $UserToken = $event['source']['userId'];
            $ReplyToken = $event['replyToken'];
            $msg = "";

            $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
            $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);
            $response = $bot->getProfile($UserToken);
            if ($response->isSucceeded()) {
                $profile = $response->getJSONDecodedBody();
                $displayName = $profile['displayName'];
                $pictureUrl = $profile['pictureUrl'];
                $statusMessage = $profile['statusMessage'];


                //$sql = $conn->prepare("SELECT * FROM AccountBackend where LineId=?");
                //$sql->execute([$displayName]);
                //$accBK = $sql->fetch();

                //if(!is_null($accBK) && $accBK['TokenLineId'] == null){
                //    $sql = $conn->prepare("UPDATE AccountBackend SET TokenLineId=? WHERE LineId=?")->execute([$UserToken,$displayName]);
                //}
                $tt = "aaaa";
                $tt2 = "aaaa";
                $tt3 = "aaaa";

                $sql = "INSERT INTO Test2 (name, lastname, nickname, tokenlineid, lineid) VALUES (?,?,?,?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$tt, $tt2, $tt3, $UserToken, $displayName]);
                $conn->prepare("INSERT INTO Test (Name) VALUES ('$tt')")->execute();

                $msg = "User Token : " . $UserToken ."\n"
                ."Pisplay Name: ". $displayName."\n"
                ."Status Message: ". $statusMessage."\n"
                ."pictureUrl: ".  $pictureUrl;
            }


         

            $data = [
                'replyToken' => $ReplyToken,
                'messages' => [[
                    'type' => 'text',
                    'text' => $msg
                ]]
            ];

            $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);
            $send_result = send_reply_message($API_URL . '/reply', $POST_HEADER, $post_body);
            echo "Result: " . $send_result . "\r\n";
        }
    }
} else {
    echo "Testwebapi";
}


function send_reply_message($url, $post_header, $post_body)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}
