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
?>
