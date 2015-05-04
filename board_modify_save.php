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

// 3. 글 데이터 불러오기
$b_idx = $_POST[b_idx];
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

// 7. 파일삭제가 체크되어 있으면 파일 지우고 파일변수 설정
$dir = "./data";
$b_file = $dir."/".$b_idx;
if($_POST[b_file_del]){
    @unlink($b_file);
    $b_filename = "";
    $b_filesize = 0;
    $file_sql = ", b_filename = '".$b_filename."', b_filesize = '".$b_filesize."'";
}

// 8. 파일 변수 만들기
if($_FILES[b_file][tmp_name]){
    $b_filename = $_FILES[b_file][name];
    $b_filesize = $_FILES[b_file][size];
    $file_sql = ", b_filename = '".$b_filename."', b_filesize = '".$b_filesize."'";
}

// 7. 글저장
$sql = "update ".$_cfg['board_table']." set b_title = '".addslashes(htmlspecialchars($_POST[b_title]))."', b_contents = '".addslashes(htmlspecialchars($_POST[b_contents]))."', b_is_secret = '".$_POST[b_is_secret]."', b_pass = '".$_POST[b_pass]."' ".$file_sql." where b_idx = '".$b_idx."'";
sql_query($sql);


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

// 12. 글목록 페이지로 보내기
alert("글이 저장 되었습니다.", "./board_view.php?bc_code=".$bc_code."&b_idx=".$b_idx."&page=".$_POST[page]);
?>
