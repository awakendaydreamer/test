<!DOCTYPE html>
<?
// 1. 공통 인클루드 파일
include "./admin_head.php";

// 2. 회원 설정 데이터 불러오기
$sql = "select * from bd__member where m_idx = '".$_GET[m_idx]."'";
$data = sql_fetch($sql);
if(!$data[m_idx]){
    alert("없는 회원입니다.");
}
// 3. 입력 HTML 출력
?>
<br/>
<table style="width:1000px;height:30px;border:5px #CCCCCC solid;">
    <tr>
        <td align="center" valign="middle" style="font-zise:15px;font-weight:bold;">회원 정보수정</td>
    </tr>
</table>
<br/>
<form name="bWriteForm" method="post" action="./admin_member_modify_save.php" style="margin:0px;">
<input type="hidden" name="m_idx" value="<?=$data[m_idx]?>">
<table style="width:1000px;height:30px;border:0px;">
    <tr>
        <td align="center" valign="middle" style="width:200px;height:30px;background-color:#CCCCCC;">회원아이디</td>
        <td align="left" valign="middle" style="width:800px;height:30px;">&nbsp;<?=$data[m_id]?></td>
    </tr>
    <tr>
        <td align="center" valign="middle" style="width:200px;height:30px;background-color:#CCCCCC;">회원이름</td>
        <td align="left" valign="middle" style="width:800px;height:30px;"><input type="text" name="m_name" style="width:780px;" value="<?=$data[m_name]?>"></td>
    </tr>
    <tr>
        <td align="center" valign="middle" style="width:200px;height:30px;background-color:#CCCCCC;">레벨</td>
        <td align="left" valign="middle" style="width:800px;height:30px;">
        &nbsp;
        <select name="m_level">
            <option value="1" <?if($data[m_level] == 1){echo "selected";}?>>일반회원</option>
            <option value="9" <?if($data[m_level] == 9){echo "selected";}?>>어드민</option>
        </select>
        </td>
    </tr>
    <!-- 4. 수정 버튼 클릭시 입력필드 검사 함수 write_save 실행 -->
    <tr>
        <td align="center" valign="middle" colspan="2"><input type="button" value=" 회원수정 " onClick="write_save();">&nbsp;&nbsp;&nbsp;<input type="button" value=" 삭제 " onClick="location.replace('./admin_member_delete.php?m_idx=<?=$data[m_idx]?>')">&nbsp;&nbsp;&nbsp;<input type="button" value=" 뒤로가기 " onClick="history.back();"></td>
    </tr>
</table>
</form>
<script>
// 5.입력필드 검사함수
function write_save()
{
    // 6.form 을 f 에 지정
    var f = document.bWriteForm;

    // 7.입력폼 검사

    if(f.m_name.value == ""){
        alert("회원이름을 입력해 주세요.");
        return false;
    }

    // 8.검사가 성공이면 form 을 submit 한다
    f.submit();

}
</script>
