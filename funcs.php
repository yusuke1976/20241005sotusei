<?php
//XSS対応（ echoする場所で使用！それ以外はNG ）
function h($str){
    return htmlspecialchars($str, ENT_QUOTES);
}

//DB接続
function db_conn(){
  $prod_db = "";    //本番環境データベース
  $prod_host = "";    //本番環境ホスト
  $prod_id = "";      //本番環境ID
  $prod_pw = "";      //本番環境Pw
try {      
  return new PDO('mysql:dbname='. $prod_db . ';charset=utf8;host=' . $prod_host , $prod_id , $prod_pw);
} catch (PDOException $e) {
exit('DB Connection Error:'.$e->getMessage());
}
}

//SQLエラー
function sql_error($stmt){
    //execute（SQL実行時にエラーがある場合）
    $error = $stmt->errorInfo();
    exit("SQLError:".$error[2]);
}

//リダイレクト
function redirect($file_name){
    header("Location: ".$file_name);
    exit();
}

//SessionCheck(スケルトン)
function sschk(){
  if(!isset($_SESSION["chk_ssid"]) || $_SESSION["chk_ssid"]!=session_id()){
    exit("Login Error");
  }else{
    session_regenerate_id(true); //session keyを入れ替える！
    $_SESSION["chk_ssid"] = session_id();
  }
}
