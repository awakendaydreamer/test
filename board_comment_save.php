<!DOCTYPE html>
<?
// 1. 공통 인클루드 파일
include ("./head.php");

// 2. 게시판 코드 검사
$bc_code = $_POST[bc_code];
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

if($u_level < $board_config[bc_comment_level]){
    alert("권한이 없습니다.");
}

// 6. 해당 글이 있는지체크
$b_idx = $_POST[b_idx];
$sql = "select * from ".$_cfg['board_table']." where bc_code = '".$bc_code."' and b_idx = '".$b_idx."'";
$data = sql_fetch($sql);
if(!$data[b_idx]){
    alert("존재 하지 않는 글입니다.");
}

// 7. 넘어온 변수 검사

if(trim($_POST[m_name]) == ""){
    alert("이름을 입력해 주세요.");
}

if(trim($_POST[co_contents]) == ""){
    alert("댓글내용을 입력해 주세요.");
}


// 8. 글저장
$sql = "insert into ".$_cfg['comment_table']." set b_idx = '".$b_idx."', m_id = '".$_SESSION[user_id]."', m_name = '".addslashes(htmlspecialchars($_POST[m_name]))."', co_contents = '".addslashes(htmlspecialchars($_POST[co_contents]))."', co_regdate = now()";
sql_query($sql);


// 9. 글보기 페이지로 보내기
alert("댓글이 저장 되었습니다.", "./board_view.php?bc_code=".$bc_code."&b_idx=".$b_idx."&page=".$_POST[page]);
?>
