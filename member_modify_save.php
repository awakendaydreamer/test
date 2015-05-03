<!DOCTYPE html>
<?
// 1. 공통 인클루드 파일
include ("./head.php");

// 2. 로그인 안한 회원은 로그인 페이지로 보내기
if(!$_SESSION[user_id]){
    alert("로그인 하셔야 합니다.", "./login.php");
}

// 3. 넘어온 변수 검사
if($_POST[m_name] == ""){
    alert("이름을 입력해 주세요.");
}

if($_POST[m_pass] == ""){
    alert("비밀번호를 입력해 주세요.");
}

if($_POST[m_pass] != $_POST[m_pass2]){
    alert("비밀번호를 확인해 주세요.");
}


// 4. 회원정보 적기
$sql = "update bd__member set m_name = '".$_POST[m_name]."', m_pass = '".$_POST[m_pass]."' where m_id = '".$_SESSION[user_id]."'";
sql_query($sql);

// 5. 첫 페이지로 보내기
alert("회원정보가 수정 되었습니다.", "index.php");
?>
