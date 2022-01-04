<?php
require "vendor/autoload.php";
require "Database.php";
require_once('vendor/linecorp/line-bot-sdk/line-bot-sdk-tiny/LINEBotTiny.php');

if(isset($_GET['DD'])){

    $strAccessToken = "your_access_token";
     
    $strUrl = "https://api.line.me/v2/bot/message/push";
     
    $arrHeader = array();
    $arrHeader[] = "Content-Type: application/json";
    $arrHeader[] = "Authorization: Bearer {$strAccessToken}";
     
    $sqlUser = $conn->prepare("SELECT * FROM AccountBackend WHERE Id = ".$_GET['DD']."");
    $sqlUser->execute();

    $AccountBackend = $sqlUser->fetch();

    if($AccountBackend['TokenLineId'] != null && $AccountBackend['TokenLineId'] != ""){

        $arrPostData = array();
        $arrPostData['to'] = $AccountBackend['TokenLineId'];
        $arrPostData['messages'][0]['type'] = "text";
        $arrPostData['messages'][0]['text'] = "คุณไม่ได้อัพเดทข้อมูล";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$strUrl);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeader);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrPostData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close ($ch);
    }
}


?>