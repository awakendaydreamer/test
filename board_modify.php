<!DOCTYPE html>
<?
// 1. 공통 인클루드 파일
include ("./head.php");

// 2. 게시판 코드 검사
$bc_code = $_GET[bc_code];
if($bc_code){
    // 3. 게시판 코드가 있으면 게시판 설정 불러오기
    $b_config_sql = "select * from ".$_cfg['config_table']." where bc_code = '".$bc_code."'";
    $board_config = sql_fetch($b_config_sql);
}else{
    alert("게시판 코드가 없습니다.");
}

// 4. 존재하는 게시판인지 확인
if(!$board_config[bc_idx]){
    alert("존재 하지 않는 게시판입니다.");
}

// 5. 게시판 권한 체크
if($_SESSION[user_level]){
    $u_level = $_SESSION[user_level];
}else{
    $u_level = 0;
}

// 3. 글 데이터 불러오기
$b_idx = $_GET[b_idx];
$sql = "select * from ".$_cfg['board_table']." where bc_code = '".$bc_code."' and b_idx = '".$b_idx."'";
$data = sql_fetch($sql);

// 4. 글이 없으면 메세지 출력후 되돌리기
if(!$data[b_idx]){
    alert("존재하지 않는 글입니다.");
}

// 5. 본인의 글이 아니면 메세지 출력후 되돌리기
if($data[m_id] != $_SESSION[user_id] && $u_level != 9){
    alert("본인의 글이 아닙니다.");
}

// 6. 게시판 상단 이미지 출력
$dir = "./data/board_config";
$head_file = $dir."/".$board_config[bc_idx]."_head";

if($board_config[bc_head_file] && file_exists($head_file)){
?>
<br/>
<img src="<?=$head_file?>">
<?
}

// 7. 게시판 상단 내용 출력
if($board_config[bc_head]){
?>
<br/>
<table border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td align="left" valign="middle" style="font-zise:15px;"><?=$board_config[bc_head]?></td>
    </tr>
</table>
<?
}

// 8. 입력 HTML 출력
?>
<br/>
<table style="width:1000px;height:50px;border:5px #CCCCCC solid;">
    <tr>
        <td align="center" valign="middle" style="font-zise:15px;font-weight:bold;">글수정</td>
    </tr>
</table>
<br/>
<form name="bWriteForm" method="post" enctype="multipart/form-data" action="./board_modify_save.php" style="margin:0px;">
<input type="hidden" name="bc_code" value="<?=$bc_code?>">
<input type="hidden" name="b_idx" value="<?=$b_idx?>">
<input type="hidden" name="page" value="<?=$_GET[page]?>">
<table style="width:1000px;height:50px;border:0px;">
    <tr>
        <td align="center" valign="middle" style="width:200px;height:50px;background-color:#CCCCCC;">글제목</td>
        <td align="left" valign="middle" style="width:800px;height:50px;"><input type="text" name="b_title" style="width:780px;" value="<?=$data[b_title]?>"></td>
    </tr>

    <?
    // 10. 비밀글을 사용하면 비밀글 체크 여부와 비밀번호 입력받기
    if($board_config[bc_use_secret]){
    ?>
    <tr>
        <td align="center" valign="middle" style="width:200px;height:50px;background-color:#CCCCCC;">비밀글 여부</td>
        <td align="left" valign="middle" style="width:800px;height:50px;"><input type="checkbox" name="b_is_secret" value="1" <?if($data[b_is_secret] == 1)echo "checked";?>> 비밀글이면 체크</td>
    </tr>
    <tr>
        <td align="center" valign="middle" style="width:200px;height:50px;background-color:#CCCCCC;">비밀번호</td>
        <td align="left" valign="middle" style="width:800px;height:50px;"><input type="text" name="b_pass" style="width:100px;"  value="<?=$data[b_pass]?>"></td>
    </tr>
    <?
    }

    // 11. 파일 업로드를 사용하면 파일 입력
    if($board_config[bc_use_file]){
    ?>
    <tr>
        <td align="center" valign="middle" style="width:200px;height:50px;background-color:#CCCCCC;">첨부파일</td>
        <td align="left" valign="middle" style="width:800px;height:50px;">
        <?
        // 11-1 파일이 있으면
        if($data[b_filename]){
        ?>
        <a href="./download.php?bc_code=<?=$bc_code?>&b_idx=<?=$b_idx?>"><?=$data[b_filename]?></a> <input type="checkbox" name="b_file_del" value="1"> 삭제<br>
        <?}?>
        <input type="file" name="b_file" >
        </td>
    </tr>
    <?
    }
    ?>
    <tr>
        <td align="center" valign="middle" style="width:200px;height:200px;background-color:#CCCCCC;">글내용</td>
        <td align="left" valign="middle" style="width:800px;height:200px;">
        <textarea name="b_contents" style="width:800px;height:200px;"><?=$data[b_contents]?></textarea>
        </td>
    </tr>
    <!-- 12. 수정하기 버튼 클릭시 입력필드 검사 함수 write_save 실행 -->
    <tr>
        <td align="center" valign="middle" colspan="2"><input type="button" value=" 수정하기 " onClick="write_save();">&nbsp;&nbsp;&nbsp;<input type="button" value=" 뒤로가기 " onClick="history.back();"></td>
    </tr>
</table>
</form>
<script>
// 13.입력필드 검사함수
function write_save()
{
    // 14.form 을 f 에 지정
    var f = document.bWriteForm;

    // 15.입력폼 검사

    if(f.b_title.value == ""){
        alert("글제목을 입력해 주세요.");
        return false;
    }

    <?
    // 16. 비밀글을 사용하면 비밀글 체크 여부와 비밀번호 입력 체크
    if($board_config[bc_use_secret]){
    ?>
    if(f.b_is_secret.checked == true && f.b_pass.value == ""){
        alert("비밀번호를 입력해 주세요.");
        return false;
    }
    <?
    }
    ?>

    if(f.b_contents.value == ""){
        alert("글내용을 입력해 주세요.");
        return false;
    }

    // 17.검사가 성공이면 form 을 submit 한다
    f.submit();

}
</script>

<?
// 18. 게시판 하단 내용 출력
if($board_config[bc_tail]){
?>
<br/>
<table border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td align="left" valign="middle" style="font-zise:15px;"><?=$board_config[bc_tail]?></td>
    </tr>
</table>
<?
}

// 19. 게시판 하단 이미지 출력
$dir = "./data/board_config";
$tail_file = $dir."/".$board_config[bc_idx]."_tail";

if($board_config[bc_tail_file] && file_exists($tail_file)){
?>
<br/>
<img src="<?=$tail_file?>">
<?
}
?>
