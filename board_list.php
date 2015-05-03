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

if($u_level < $board_config[bc_list_level]){
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

// 8.게시판 목록 출력
?>
<br/>
<table style="width:1000px;height:50px;border:5px #CCCCCC solid;">
    <tr>
        <td align="center" valign="middle" style="font-zise:15px;font-weight:bold;">글목록</td>
    </tr>
</table>
<br/>
<table cellspacing="1" style="width:1000px;height:50px;border:0px;background-color:#999999;">
    <tr>
        <td align="center" valign="middle" width="5%" style="height:30px;background-color:#CCCCCC;">번호</td>
        <td align="center" valign="middle" width="60%" style="height:30px;background-color:#CCCCCC;">글제목</td>
        <td align="center" valign="middle" width="15%" style="height:30px;background-color:#CCCCCC;">글쓴이</td>
        <td align="center" valign="middle" width="20%" style="height:30px;background-color:#CCCCCC;">작성일</td>
    </tr>
<?

// 9. 페이지 변수 설정
if($_GET[page] && $_GET[page] > 0){
    // 현재 페이지 값이 존재하고 0 보다 크면 그대로 사용
    $page = $_GET[page];
}else{
    // 그 외의 경우는 현재 페이지를 1로 설정
    $page = 1;
}
// 한 페이지에 보일 글 수
$page_row = 10;
// 한줄에 보여질 페이지 수
$page_scale = 10;
// 페이징을 출력할 변수 초기화
$paging_str = "";

// 10. 전체 글 갯수 알아내기
$sql = "select count(*) as cnt from ".$_cfg['board_table']." where bc_code = '".$bc_code."' ";
$total_count = sql_total($sql);

// 11. 페이지 출력 내용 만들기
$paging_str = paging($page, $page_row, $page_scale, $total_count, "bc_code=".$bc_code);

// 12. 시작 열을 구함
$from_record = ($page - 1) * $page_row;

// 13. 글목록 구하기
$query = "select * from ".$_cfg['board_table']." where bc_code = '".$bc_code."' order by b_num desc, b_reply asc limit ".$from_record.", ".$page_row;
$result = mysql_query($query, $connect);

// 14.데이터 갯수 체크를 위한 변수 설정
$i = 0;

// 15.데이터가 있을 동안 반복해서 값을 한 줄씩 읽기
while($data = mysql_fetch_array($result)){

    // 16. 댓글 앞에 붙을 기호 만들기
    $reply_str = "";
    $reply_depth = strlen($data[b_reply]);
    if ($reply_depth > 0){
        for ($k=0; $k<$reply_depth; $k++){
            $reply_str .= '&nbsp;&nbsp;&nbsp;';
        }
        $reply_str .= "┗";
    }

    // 17. 게시글 링크 및 비밀글표시 만들기
    $mark_secret = "";
    if($data[b_is_secret]){
        $mark_secret = "[비밀글] ";
    }
    // 게시글을 볼 권한 여부에 따라서
    if($u_level >= $board_config[bc_read_level]){
        // 비밀글 여부 따지기
        if($data[b_is_secret]){
            // 글쓴이와 관리자 여부 따지기
            if($_SESSION[user_id] == $data[m_id] || $_SESSION[user_id] == 9 || ($_SESSION[user_id] == $board_config[bc_admin] && $board_config[bc_admin])){
                $article_link = "./board_view.php?bc_code=".$bc_code."&b_idx=".$data[b_idx]."&page=".$page;
            }else{
                $article_link = "./board_password.php?bc_code=".$bc_code."&b_idx=".$data[b_idx]."&page=".$page;
            }
        }else{
            $article_link = "./board_view.php?bc_code=".$bc_code."&b_idx=".$data[b_idx]."&page=".$page;
        }
    }else{
        $article_link = "javascript:alert('글을 읽을 권한이 없습니다.');";
    }


?>
    <tr>
        <td align="center" valign="middle" style="height:30px;background-color:#FFFFFF;"><?=($total_count - (($page - 1) * $page_row) - $i )?></td>
        <td align="left" valign="middle" style="height:30px;background-color:#FFFFFF;">&nbsp;<?=$reply_str?><a href="<?=$article_link?>"><?=$mark_secret?><?=$data[b_title]?></a></td>
        <td align="center" valign="middle" style="height:30px;background-color:#FFFFFF;"><?=$data[m_name]?></td>
        <td align="center" valign="middle" style="height:30px;background-color:#FFFFFF;"><?=substr($data[b_regdate],0,10)?></td>
    </tr>
<?
    // 17.데이터 갯수 체크를 위한 변수를 1 증가시킴
    $i++;
}

// 18.데이터가 하나도 없으면
if($i == 0){
?>
    <tr>
        <td align="center" valign="middle" colspan="4" style="height:50px;background-color:#FFFFFF;">게시글이 하나도 없습니다.</td>
    </tr>
<?
}
?>
</table>
<br/>
<table style="width:1000px;height:50px;">
    <tr>
        <td align="center" valign="middle"><?=$paging_str?></td>
    </tr>
    <?// 19. 권한 체크 후 글쓰기 버튼 보여주기?>
    <?if($u_level >= $board_config[bc_write_level]){?>
    <tr>
        <td align="center" valign="middle"><input type="button" value=" 글쓰기 " onClick="location.href='./board_write.php?bc_code=<?=$bc_code?>';"></td>
    </tr>
    <?}?>
</table>
<?
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
