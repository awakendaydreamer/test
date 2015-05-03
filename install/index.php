<!DOCTYPE html>
<?
//######################################
//
//  설치데이터 입력파일 : install/index.php
//
//######################################
// 1. db.php 파일의 존재유무로 설치 했는지 확인 (존재하면 설치한것임)
if(file_exists("../db.php")){
    ?>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <script>
    alert("이미 설치가 되어 있습니다.");
    location.replace("../index.php");
    </script>
    <?
    exit;
}

// 2. 게시판의 최상단 디렉토리가 쓰기가능인지 검사
if (!is_writeable(".."))
{
    ?>
    <html>
    <head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <title></title>
    </head>
    <script>
        alert("최상위 디렉토리의 퍼미션을 707 이나 777 로 변경하여 주세요.");
    </script>
    <body>
    최상위 디렉토리의 퍼미션을 707 이나 777 로 변경하여 주세요.
    </body>
    </html>
    <?
    exit;
}

// 3. 정보를 받는 HTML 만들기
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
<title>게시판 설치</title>
</head>
<body>
<form name="iForm" method="post" action="install.php">
<table width="500">
    <tr>
        <td colspan="2" align="center"><b>MySql 정보</b></td>
    </tr>
    <tr>
        <td width="200">호스트명</td>
        <td width="300"><input type="text" name="host"></td>
    </tr>
    <tr>
        <td width="200">사용자 ID</td>
        <td width="300"><input type="text" name="user"></td>
    </tr>
    <tr>
        <td width="200">비밀번호</td>
        <td width="300"><input type="text" name="pass"></td>
    </tr>
    <tr>
        <td width="200">DB명</td>
        <td width="300"><input type="text" name="db_name"></td>
    </tr>
</table>
<br>
<table width="500">
    <tr>
        <td colspan="2" align="center"><b>관리자 정보</b></td>
    </tr>
    <tr>
        <td width="200">아이디</td>
        <td width="300"><input type="text" name="admin_id"></td>
    </tr>
    <tr>
        <td width="200">이름</td>
        <td width="300"><input type="text" name="admin_name"></td>
    </tr>
    <tr>
        <td width="200">비밀번호</td>
        <td width="300"><input type="text" name="admin_pass"></td>
    </tr>
</table>
<br>
<table width="500">
    <tr>
        <td colspan="2" align="center"><a href="javascript:install();">설치하기</a></td>
    </tr>
</table>
</form>
<script>
// 4. 정보입력을 검사하는 함수
function install()
{
    var f = document.iForm;

    if(!f.host.value){
        alert("MySql 호스트명를 적어주세요.");
        return;
    }

    if(!f.user.value){
        alert("MySql 사용자 ID를 적어주세요.");
        return;
    }

    if(!f.db_name.value){
        alert("MySql 비밀번호를 적어주세요.");
        return;
    }

    if(!f.host.value){
        alert("MySql DB명을 적어주세요.");
        return;
    }

    if(!f.admin_id.value){
        alert("관리자 아이디를 적어주세요.");
        return;
    }

    if(!f.admin_name.value){
        alert("관리자 이름을 적어주세요.");
        return;
    }

    if(!f.admin_pass.value){
        alert("관리자 비밀번호를 적어주세요.");
        return;
    }

    f.submit();
}
</script>
</body>
</html>
