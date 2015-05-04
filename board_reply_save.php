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

if($u_level < $board_config[bc_reply_level]){
    alert("권한이 없습니다.", "./index.php");
}

// 6. 부모글정보 가져오기
$b_idx = $_POST[b_idx];
$sql = "select * from ".$_cfg['board_table']." where bc_code = '".$bc_code."' and b_idx = '".$b_idx."'";
$data = sql_fetch($sql);

// 7. 해당 글이 있는지 와 비밀글이면 비밀번호 입력여부 체크체크
if(!$data[b_idx]){
    alert("존재 하지 않는 글입니다.");
}

// 8. 댓글이 가능한지 검사
// 8-1. 부모글의 단계가 몇단계 인지 검사후 3단계면 댓글 불가
if(strlen($data[b_reply]) == 3){
    alert("더이상 댓글을 쓸수가 없습니다.");
}

// 8-2 부모글에 달린 댓글의 마지막 댓글이 몇번째인지 검사

$sql2 = "select * from ".$_cfg['board_table']." where bc_code = '".$bc_code."' and b_num = '".$data[b_num]."' and b_reply like '".$data[b_reply]."%' order by b_reply desc limit 1";
$result2 = sql_query($sql2);
$data2 = mysql_fetch_array($result2);

$last_reply_char = substr($data2[b_reply], strlen($data[b_reply]), 1);
if($last_reply_char == "Z"){
    alert("더이상 댓글을 쓸수가 없습니다.");
}

// 9. 넘어온 변수 검사
if(trim($_POST[b_title]) == ""){
    alert("글제목을 입력해 주세요.");
}

if(trim($_POST[b_contents]) == ""){
    alert("글내용을 입력해 주세요.");
}

if($board_config[bc_use_secret] && $_POST[b_is_secret] == 1 && trim($_POST[b_pass]) == ""){
    alert("비밀번호를 입력해 주세요.");
}

// 10. b_num 과 b_reply 만들기
$b_num = $data[b_num];
if($last_reply_char){
    $b_reply = $data[b_reply].chr(ord($last_reply_char) + 1);
}else{
    $b_reply = $data[b_reply]."A";
}

// 11. 파일 변수 만들기
if($_FILES[b_file][tmp_name]){
    $b_filename = $_FILES[b_file][name];
    $b_filesize = $_FILES[b_file][size];
}else{
    $b_filename = "";
    $b_filesize = 0;
}

// 12. 글저장
$sql = "insert into ".$_cfg['board_table']." set bc_code = '".$bc_code."', b_num = '".$b_num."', b_reply = '".$b_reply."', m_id = '".$_SESSION[user_id]."', m_name = '".addslashes(htmlspecialchars($_POST[m_name]))."', b_title = '".addslashes(htmlspecialchars($_POST[b_title]))."', b_contents = '".addslashes(htmlspecialchars($_POST[b_contents]))."', b_is_secret = '".$_POST[b_is_secret]."', b_pass = '".$_POST[b_pass]."', b_filename = '".$b_filename."', b_filesize = '".$b_filesize."', b_regdate = now()";
sql_query($sql);

// 13. 저장된 글번호 찾기
$b_idx = mysql_insert_id();


// 14. 파일저장
$dir = "./data";
$b_file = $dir."/".$b_idx;

if($_FILES[b_file][tmp_name] && $b_filename){
    if(file_exists($b_file)){
        @unlink($b_file);
    }
    move_uploaded_file($_FILES[b_file][tmp_name], $b_file);
    chmod($b_file, 0666);

}

// 15. 글목록 페이지로 보내기
alert("글이 저장 되었습니다.", "./board_list.php?bc_code=".$bc_code."&page=".$_POST[page]);
?>
