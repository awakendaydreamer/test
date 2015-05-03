<!DOCTYPE html>
<?
// 1. 공통 인클루드
include "./inc/config.php";

// 2. 로그인 입력 HTML
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
<title></title>
</head>
<table style="width:1000px;height:50px;border:5px #CCCCCC solid;">
    <tr>
        <td align="center" valign="middle" colspan="3" style="font-zise:15px;font-weight:bold;">
        PHPer's Heaven 설치형게시판 관리자
        </td>
    </tr>
</table>
<br/>
<table style="width:1000px;height:50px;border:5px #CCCCCC solid;">
    <tr>
        <td align="center" valign="middle" style="font-zise:15px;font-weight:bold;">관리자 로그인</td>
    </tr>
</table>
<br/>
<form name="loginForm" method="post" action="./admin_login_chk.php" style="margin:0px;">
<table style="width:1000px;height:50px;border:0px;">
    <tr>
        <td align="center" valign="middle" style="width:200px;height:50px;background-color:#CCCCCC;">아이디</td>
        <td align="left" valign="middle" style="width:800px;height:50px;"><input type="text" name="m_id" style="width:380px;"></td>
    </tr>
    <tr>
        <td align="center" valign="middle" style="width:200px;height:50px;background-color:#CCCCCC;">비밀번호</td>
        <td align="left" valign="middle" style="width:800px;height:50px;"><input type="password" name="m_pass" style="width:380px;"></td>
    </tr>
    <!-- 3. 로그인 버튼 클릭시 입력필드 검사 함수 login_chk 실행 -->
    <tr>
        <td align="center" valign="middle" colspan="2"><input type="button" value=" 로그인 " onClick="login_chk();"></td>
    </tr>
</table>
</form>
<script>
// 4.입력필드 검사함수
function login_chk()
{
    // 6.form 을 f 에 지정
    var f = document.loginForm;

    // 7.입력폼 검사
    if(f.m_id.value == ""){
        // 8.값이 없으면 경고창으로 메세지 출력 후 함수 종료
        alert("아이디를 입력해 주세요.");
        return false;
    }

    if(f.m_pass.value == ""){
        alert("비밀번호를 입력해 주세요.");
        return false;
    }

    // 9.검사가 성공이면 form 을 submit 한다
    f.submit();

}
</script>
