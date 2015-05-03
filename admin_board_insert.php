<!DOCTYPE html>
<?
// 1. 공통 인클루드 파일
include "./admin_head.php";

// 2. 입력 HTML 출력
?>
<br/>
<table style="width:1000px;height:30px;border:5px #CCCCCC solid;">
    <tr>
        <td align="center" valign="middle" style="font-zise:15px;font-weight:bold;">게시판 생성</td>
    </tr>
</table>
<br/>
<form name="bWriteForm" method="post" enctype="multipart/form-data" action="./admin_board_insert_save.php" style="margin:0px;">
<table style="width:1000px;height:30px;border:0px;">
    <tr>
        <td align="center" valign="middle" style="width:200px;height:30px;background-color:#CCCCCC;">게시판코드</td>
        <td align="left" valign="middle" style="width:800px;height:30px;"><input type="text" name="bc_code" style="width:780px;"></td>
    </tr>
    <tr>
        <td align="center" valign="middle" style="width:200px;height:30px;background-color:#CCCCCC;">게시판이름</td>
        <td align="left" valign="middle" style="width:800px;height:30px;"><input type="text" name="bc_name" style="width:780px;"></td>
    </tr>
    <tr>
        <td align="center" valign="middle" style="width:200px;height:30px;background-color:#CCCCCC;">게시판 상단이미지</td>
        <td align="left" valign="middle" style="width:800px;height:30px;"><input type="file" name="bc_head_file" size="80"></td>
    </tr>
    <tr>
        <td align="center" valign="middle" style="width:200px;height:30px;background-color:#CCCCCC;">게시판상단내용</td>
        <td align="left" valign="middle" style="width:800px;height:100px;"><textarea name="bc_head" style="width:800px;height:100px;"></textarea></td>
    </tr>
    <tr>
        <td align="center" valign="middle" style="width:200px;height:30px;background-color:#CCCCCC;">게시판하단내용</td>
        <td align="left" valign="middle" style="width:800px;height:100px;"><textarea name="bc_tail" style="width:800px;height:100px;"></textarea></td>
    </tr>
    <tr>
        <td align="center" valign="middle" style="width:200px;height:30px;background-color:#CCCCCC;">게시판하단이미지</td>
        <td align="left" valign="middle" style="width:800px;height:30px;"><input type="file" name="bc_tail_file" size="80"></td>
    </tr>
    <tr>
        <td align="center" valign="middle" style="width:200px;height:120px;background-color:#CCCCCC;">권한설정</td>
        <td align="left" valign="middle" style="width:800px;height:120px;">
        목록 레벨 : <select name="bc_list_level">
        <?for($i=0;$i<=9;$i++){?>
            <option value="<?=$i?>"><?=$i?></option>
        <?}?>
        </select><br>
        읽기 레벨 : <select name="bc_read_level">
        <?for($i=0;$i<=9;$i++){?>
            <option value="<?=$i?>"><?=$i?></option>
        <?}?>
        </select><br>
        쓰기 레벨 : <select name="bc_write_level">
        <?for($i=0;$i<=9;$i++){?>
            <option value="<?=$i?>"><?=$i?></option>
        <?}?>
        </select><br>
        답글 레벨 : <select name="bc_reply_level">
        <?for($i=0;$i<=9;$i++){?>
            <option value="<?=$i?>"><?=$i?></option>
        <?}?>
        </select><br>
        댓글 레벨 : <select name="bc_comment_level">
        <?for($i=0;$i<=9;$i++){?>
            <option value="<?=$i?>"><?=$i?></option>
        <?}?>
        </select>
        </td>
    </tr>
    <tr>
        <td align="center" valign="middle" style="width:200px;height:30px;background-color:#CCCCCC;">게시판 관리자ID</td>
        <td align="left" valign="middle" style="width:800px;height:30px;"><input type="text" name="bc_admin" style="width:780px;"></td>
    </tr>
    <tr>
        <td align="center" valign="middle" style="width:200px;height:30px;background-color:#CCCCCC;">파일 업로드 사용</td>
        <td align="left" valign="middle" style="width:800px;height:30px;"><input type="checkbox" name="bc_use_file" value="1"> 사용</td>
    </tr>
    <tr>
        <td align="center" valign="middle" style="width:200px;height:30px;background-color:#CCCCCC;">비밀글 사용</td>
        <td align="left" valign="middle" style="width:800px;height:30px;"><input type="checkbox" name="bc_use_secret" value="1"> 사용</td>
    </tr>
    <tr>
        <td align="center" valign="middle" style="width:200px;height:30px;background-color:#CCCCCC;">답글 사용</td>
        <td align="left" valign="middle" style="width:800px;height:30px;"><input type="checkbox" name="bc_use_reply" value="1"> 사용</td>
    </tr>
    <tr>
        <td align="center" valign="middle" style="width:200px;height:30px;background-color:#CCCCCC;">댓글 사용</td>
        <td align="left" valign="middle" style="width:800px;height:30px;"><input type="checkbox" name="bc_use_comment" value="1"> 사용</td>
    </tr>
    <!-- 3. 생성 버튼 클릭시 입력필드 검사 함수 write_save 실행 -->
    <tr>
        <td align="center" valign="middle" colspan="2"><input type="button" value=" 게시판생성 " onClick="write_save();">&nbsp;&nbsp;&nbsp;<input type="button" value=" 뒤로가기 " onClick="history.back();"></td>
    </tr>
</table>
</form>
<script>
// 4.입력필드 검사함수
function write_save()
{
    // 5.form 을 f 에 지정
    var f = document.bWriteForm;

    // 6.입력폼 검사

    if(f.bc_code.value == ""){
        alert("게시판코드를 입력해 주세요.");
        return false;
    }

    if(f.bc_name.value == ""){
        alert("게시판이름을 입력해 주세요.");
        return false;
    }

    // 7.검사가 성공이면 form 을 submit 한다
    f.submit();

}
</script>
