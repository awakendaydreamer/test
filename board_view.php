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

if($u_level < $board_config[bc_read_level]){
    alert("권한이 없습니다.", "./index.php");
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

// 8. 페이징 변수 설정
if($_GET[page] && $_GET[page] > 0){
    // 현재 페이지 값이 존재하고 0 보다 크면 그대로 사용
    $page = $_GET[page];
}else{
    // 그 외의 경우는 현재 페이지를 1로 설정
    $page = 1;
}

// 9. 글정보 가져오기
$b_idx = $_GET[b_idx];
$sql = "select * from ".$_cfg['board_table']." where bc_code = '".$bc_code."' and b_idx = '".$b_idx."'";
$data = sql_fetch($sql);

// 10. 해당 글이 있는지 와 비밀글이면 비밀번호 입력여부 체크체크
if(!$data[b_idx]){
    alert("존재 하지 않는 글입니다.");
}

if($data[b_is_secret] && !$_SESSION["b_pass_".$b_idx] && $_SESSION[user_id] != $data[m_id] && $u_level != 9){
    alert("비밀번호를 입력하여 주세요.");
}

// 11.글내용 출력
?>
<br/>
<table style="width:1000px;height:50px;border:5px #CCCCCC solid;">
    <tr>
        <td align="center" valign="middle" style="font-zise:15px;font-weight:bold;">글보기</td>
    </tr>
</table>
<table style="width:1000px;height:50px;border:0px;">
    <tr>
        <td align="center" valign="middle" style="width:200px;height:50px;background-color:#CCCCCC;">글제목</td>
        <td align="left" valign="middle" style="width:800px;height:50px;"><?=$data[b_title]?></td>
    </tr>
    <tr>
        <td align="center" valign="middle" style="width:200px;height:50px;background-color:#CCCCCC;">작성자명</td>
        <td align="left" valign="middle" style="width:800px;height:50px;"><?=$data[m_name]?></td>
    </tr>
    <?

    // 12. 파일 업로드를 사용하면 파일 입력
    if($board_config[bc_use_file] && $data[b_filename]){
    ?>
    <tr>
        <td align="center" valign="middle" style="width:200px;height:50px;background-color:#CCCCCC;">첨부파일</td>
        <td align="left" valign="middle" style="width:800px;height:50px;"><a href="./download.php?bc_code=<?=$bc_code?>&b_idx=<?=$b_idx?>"><?=$data[b_filename]?></a></td>
    </tr>
    <?
    }
    ?>
    <tr>
        <td align="center" valign="middle" style="width:200px;height:200px;background-color:#CCCCCC;">글내용</td>
        <td align="left" valign="middle" style="width:800px;height:200px;"><?=nl2Br($data[b_contents])?></td>
    </tr>
</table>
<br/>
<table style="width:1000px;height:50px;">
    <tr>
        <td align="center" valign="middle"><input type="button" value=" 목록보기 " onClick="location.href='./board_list.php?bc_code=<?=$bc_code?>&page=<?=$page?>';"></td>
    <?// 13. 권한 체크 후 답글쓰기 보여주기?>
    <?if($board_config[bc_use_reply ] && $u_level >= $board_config[bc_reply_level]){?>
        <td align="center" valign="middle"><input type="button" value=" 답글쓰기 " onClick="location.href='./board_reply.php?bc_code=<?=$bc_code?>&b_idx=<?=$b_idx?>&page=<?=$page?>';"></td>
    <?}?>
    <?// 13. 권한 체크 후 글수정 보여주기?>
    <?if($_SESSION[user_id] == $data[m_id] || $u_level == 9){?>
        <td align="center" valign="middle"><input type="button" value=" 글수정 " onClick="location.href='./board_modify.php?bc_code=<?=$bc_code?>&b_idx=<?=$b_idx?>&page=<?=$page?>';"></td>
    <?}?>
    <?// 13. 권한 체크 후 글삭제 버튼 보여주기?>
    <?if($_SESSION[user_id] == $data[m_id] || $u_level == 9){?>
        <td align="center" valign="middle"><input type="button" value=" 글삭제 " onClick="location.href='./board_delete.php?bc_code=<?=$bc_code?>&b_idx=<?=$b_idx?>&page=<?=$page?>';"></td>
    <?}?>
    </tr>
</table>
<?
// 13. 댓글 부분 권한 체크 및 출력 결정
if($board_config[bc_use_comment]){

    // 14. 댓글 권한 체크 후 댓글쓰기 부분 출력
    if($u_level >= $board_config[bc_comment_level]){
    ?>
    <br/>
    <table style="width:1000px;height:50px;border:5px #CCCCCC solid;">
        <tr>
            <td align="center" valign="middle" style="font-zise:15px;font-weight:bold;">댓글작성</td>
        </tr>
    </table>
    <br/>
    <form name="bWriteForm" method="post" enctype="multipart/form-data" action="./board_comment_save.php" style="margin:0px;">
    <input type="hidden" name="bc_code" value="<?=$bc_code?>">
    <input type="hidden" name="b_idx" value="<?=$b_idx?>">
    <input type="hidden" name="page" value="<?=$page?>">
    <table cellspacing="1" style="width:1000px;height:50px;border:0px;background-color:#999999;">
        <tr>
            <td align="center" valign="middle" width="100" style="height:30px;background-color:#CCCCCC;">이름</td>
            <td align="center" valign="middle" width="800" style="height:30px;background-color:#CCCCCC;">댓글내용</td>
            <td align="center" valign="middle" width="100" style="height:30px;background-color:#CCCCCC;">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" valign="middle" width="100" style="height:30px;background-color:#FFFFFF;">
            <input typr="text" name="m_name" <?if($_SESSION[user_idx]){ echo " value='".$_SESSION[user_name]."' readOnly";}?>  style="width:90px;">
            </td>
            <td align="center" valign="middle" width="800" style="height:30px;background-color:#FFFFFF;"><input type="text" name="co_contents" style="width:780px;"></td>
            <td align="center" valign="middle" width="100" style="height:30px;background-color:#FFFFFF;"><input type="button" value=" 댓글쓰기 " onClick="write_save();"></td>
        </tr>
    </table>
    <script>
    function write_save()
    {
        var f = document.bWriteForm;

        if(f.m_name.value == ""){
            alert("이름을 입력해 주세요.");
            return false;
        }

        if(f.co_contents.value == ""){
            alert("댓글내용을 입력해 주세요.");
            return false;
        }

        f.submit();
    }
    </script>
    </form>
    <?
    }


    // 15. 댓글 목록 출력
    ?>
    <br/>
    <table style="width:1000px;height:50px;border:5px #CCCCCC solid;">
        <tr>
            <td align="center" valign="middle" style="font-zise:15px;font-weight:bold;">댓글목록</td>
        </tr>
    </table>
    <br/>
    <table cellspacing="1" style="width:1000px;height:50px;border:0px;background-color:#999999;">
        <tr>
            <td align="center" valign="middle" width="5%" style="height:30px;background-color:#CCCCCC;">번호</td>
            <td align="center" valign="middle" width="60%" style="height:30px;background-color:#CCCCCC;">댓글내용</td>
            <td align="center" valign="middle" width="15%" style="height:30px;background-color:#CCCCCC;">글쓴이</td>
            <td align="center" valign="middle" width="20%" style="height:30px;background-color:#CCCCCC;">작성일</td>
        </tr>
    <?

    // 16. 전체 댓글 갯수 알아내기
    $sql = "select count(*) as cnt from ".$_cfg['comment_table']." where b_idx = '".$b_idx."' ";
    $total_count = sql_total($sql);


    // 17. 댓글목록 구하기
    $query = "select * from ".$_cfg['comment_table']." where b_idx = '".$b_idx."' order by co_idx desc ";
    $result = mysql_query($query, $connect);

    // 18.데이터 갯수 체크를 위한 변수 설정
    $i = 0;

    // 19.데이터가 있을 동안 반복해서 값을 한 줄씩 읽기
    while($data_commnent = mysql_fetch_array($result)){


    ?>
        <tr>
            <td align="center" valign="middle" style="height:30px;background-color:#FFFFFF;"><?=($total_count - $i )?></td>
            <td align="left" valign="middle" style="height:30px;background-color:#FFFFFF;">&nbsp;<?=$data_commnent[co_contents]?></td>
            <td align="center" valign="middle" style="height:30px;background-color:#FFFFFF;"><?=$data_commnent[m_name]?></td>
            <td align="center" valign="middle" style="height:30px;background-color:#FFFFFF;"><?=substr($data_commnent[co_regdate],0,10)?></td>
        </tr>
    <?
        // 21.데이터 갯수 체크를 위한 변수를 1 증가시킴
        $i++;
    }

    // 22. 댓글데이터가 하나도 없으면
    if($i == 0){
    ?>
        <tr>
            <td align="center" valign="middle" colspan="4" style="height:50px;background-color:#FFFFFF;">댓글이 하나도 없습니다.</td>
        </tr>
    <?
    }
}
// 댓글 목록 권한 체크 및 출력 결정 끝


// 20. 게시판 하단 내용 출력
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

// 21. 게시판 하단 이미지 출력
$dir = "./data/board_config";
$tail_file = $dir."/".$board_config[bc_idx]."_tail";

if($board_config[bc_tail_file] && file_exists($tail_file)){
?>
<br/>
<img src="<?=$tail_file?>">
<?
}
?>
