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

// 6. 파일 과 댓글 삭제하기
$file_sql = "select * from ".$_cfg['board_table']." where bc_code = '".$bc_code."' and b_num = '".$data[b_num]."' and b_reply like '".$data[b_reply]."%'";
$file_result = sql_query($file_sql);

while($file_data = mysql_fetch_array($file_result)){
    // 6-1 파일 삭제
    $dir = "./data";
    $b_file = $dir."/".$file_data[b_idx];
    @unlink($b_file);

    // 6-2 댓글 삭제
    $comment_delete = "delete from ".$_cfg['comment_table']." where b_idx = '".$file_data[b_idx]."'";
    sql_query($comment_delete);
}

// 7. 글 삭제하기
$sql_delete = "delete from ".$_cfg['board_table']." where bc_code = '".$bc_code."' and b_num = '".$data[b_num]."' and b_reply like '".$data[b_reply]."%'";
sql_query($sql_delete);


// 8. 글목록 페이지로 보내기
alert("글이 삭제 되었습니다.", "./board_list.php?bc_code=".$bc_code."&page=".$_GET[page]);
?>
