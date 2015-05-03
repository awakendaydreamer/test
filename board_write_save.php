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

if($u_level < $board_config[bc_write_level]){
    alert("권한이 없습니다.");
}

// 6. 넘어온 변수 검사
if(trim($_POST[b_title]) == ""){
    alert("글제목을 입력해 주세요.");
}

if(trim($_POST[b_contents]) == ""){
    alert("글내용을 입력해 주세요.");
}

if($board_config[bc_use_secret] && $_POST[b_is_secret] == 1 && trim($_POST[b_pass]) == ""){
    alert("비밀번호를 입력해 주세요.");
}

// 7. 파일 변수 만들기
if($_FILES[b_file][tmp_name]){
    $b_filename = $_FILES[b_file][name];
    $b_filesize = $_FILES[b_file][size];
}else{
    $b_filename = "";
    $b_filesize = 0;
}

// 8. 글저장
$sql = "insert into ".$_cfg['board_table']." set bc_code = '".$bc_code."', b_reply = '', m_id = '".$_SESSION[user_id]."', m_name = '".addslashes(htmlspecialchars($_POST[m_name]))."', b_title = '".addslashes(htmlspecialchars($_POST[b_title]))."', b_contents = '".addslashes(htmlspecialchars($_POST[b_contents]))."', b_is_secret = '".$_POST[b_is_secret]."', b_filename = '".$b_filename."', b_filesize = '".$b_filesize."', b_regdate = now()";
sql_query($sql);

// 9. 저장된 글번호 찾기
$b_idx = mysql_insert_id();


// 10. 파일저장
$dir = "./data";
$b_file = $dir."/".$b_idx;

if($_FILES[b_file][tmp_name] && $b_filename){
    if(file_exists($b_file)){
        @unlink($b_file);
    }
    move_uploaded_file($_FILES[b_file][tmp_name], $b_file);
    chmod($b_file, 0666);

}

// 11. 글번호 저장
$sql = "update ".$_cfg['board_table']." set b_num = '".$b_idx."' where b_idx = '".$b_idx."'";
sql_query($sql);

// 12. 글목록 페이지로 보내기
alert("글이 저장 되었습니다.", "./board_list.php?bc_code=".$bc_code);
?>
