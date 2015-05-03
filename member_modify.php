<!DOCTYPE html>
<?
// 1. 공통 인클루드 파일
include ("./head.php");

// 2. 로그인 안한 회원은 로그인 페이지로 보내기
if(!$_SESSION[user_id]){
    alert("로그인 하셔야 합니다.", "./login.php");
}

// 3. 회원 설정 데이터 불러오기
$sql = "select * from bd__member where m_idx = '".$_SESSION[user_idx]."'";
$data = sql_fetch($sql);

// 4. 입력 HTML 출력
?>
<br/>
<table style="width:1000px;height:50px;border:5px #CCCCCC solid;">
    <tr>
        <td align="center" valign="middle" style="font-zise:15px;font-weight:bold;">회원 정보 수정</td>
    </tr>
</table>
<br/>
<form name="modifyForm" method="post" action="./member_modify_save.php" style="margin:0px;">
<table style="width:1000px;height:50px;border:0px;">
    <tr>
        <td align="center" valign="middle" style="width:200px;height:50px;background-color:#CCCCCC;">회원이름</td>
        <td align="left" valign="middle" style="width:800px;height:50px;"><input type="text" name="m_name" style="width:780px;" value="<?=$data[m_name]?>"></td>
    </tr>
    <tr>
        <td align="center" valign="middle" style="width:200px;height:50px;background-color:#CCCCCC;">비밀번호</td>
        <td align="left" valign="middle" style="width:800px;height:50px;"><input type="password" name="m_pass" style="width:380px;"></td>
    </tr>
    <tr>
        <td align="center" valign="middle" style="width:200px;height:50px;background-color:#CCCCCC;">비밀번호 확인</td>
        <td align="left" valign="middle" style="width:800px;height:50px;"><input type="password" name="m_pass2" style="width:380px;"></td>
    </tr>
    <!-- 5. 정보수정 버튼 클릭시 입력필드 검사 함수 member_save 실행 -->
    <tr>
        <td align="center" valign="middle" colspan="2"><input type="button" value=" 정보수정 " onClick="member_save();"></td>
    </tr>
</table>
</form>
<script>
// 6.입력필드 검사함수
function member_save()
{
    // 7.form 을 f 에 지정
    var f = document.modifyForm;

    // 8.입력폼 검사

    if(f.m_name.value == ""){
        alert("회원이름을 입력해 주세요.");
        return false;
    }

    if(f.m_pass.value == ""){
        alert("비밀번호를 입력해 주세요.");
        return false;
    }

    if(f.m_pass.value != f.m_pass2.value){
        // 9.비밀번호와 확인이 서로 다르면 경고창으로 메세지 출력 후 함수 종료
        alert("비밀번호를 확인해 주세요.");
        return false;
    }

    // 10.검사가 성공이면 form 을 submit 한다
    f.submit();

}
</script>
