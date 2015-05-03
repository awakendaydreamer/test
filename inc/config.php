<!DOCTYPE html>
<?
//######################################
//
//  설정파일 : inc/config.php
//
//######################################

// db.php 파일의 존재유무로 설치 했는지 확인 (존재하면 설치한것임)
if(!file_exists("./db.php")){
    ?>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <script>
    alert("설치가 되지 않았습니다.");
    location.replace("./install/index.php");
    </script>
    <?
    exit;
}

// 설정변수 초기화
$_cfg = array();

// DB 테이블 정의
$_cfg['member_table'] = "bd__member";
$_cfg['config_table'] = "bd__board_config";
$_cfg['board_table'] = "bd__board";
$_cfg['comment_table'] = "bd__comment";
$_cfg['history_table'] = "bd__view_history";

// db.php 파일 인클루드
include ("./db.php");
// 사용자 정의 함수 인클루드
include ("./inc/lib.php");

// 세션사용은 위한 초기화
session_start();

// DB 연결
$connect = sql_connect($mysql_host, $mysql_user, $mysql_password, $mysql_db);
?>
