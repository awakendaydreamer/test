<!DOCTYPE html>
<?
// 1. 공통 인클루드 파일
include "./admin_head.php";

// 2. 넘어온 변수 검사
if(trim($_POST[bc_code]) == ""){
    alert("게시판코드를 입력해 주세요.");
}

$sql = "select * from bd__board_config where bc_code = '".trim($_POST[bc_code])."'";
$data = sql_fetch($sql);
if($data[bc_idx]){
    alert("이미 존재하는 게시판입니다.");
}

if(trim($_POST[bc_name]) == ""){
    alert("게시판이름을 입력해 주세요.");
}



// 3. 파일 저장 디렉토리 검사 후 없으면 생성
$dir = "./data/board_config";
if(!is_dir($dir)){
    @mkdir($dir, 0707);
    @chmod($dir, 0707);
}

// 4. 파일이 jpg나 gif 인지 검사
if($_FILES[bc_head_file][tmp_name]){
    if($_FILES[bc_head_file][type] == "image/gif" || $_FILES[bc_head_file][type] == "image/jpeg"){
        $bc_head_file = $_FILES[bc_head_file][name];
    }else{
        $bc_head_file = "";
    }
}
if($_FILES[bc_tail_file][tmp_name]){
    if($_FILES[bc_tail_file][type] == "image/gif" || $_FILES[bc_tail_file][type] == "image/jpeg"){
        $bc_tail_file = $_FILES[bc_tail_file][name];
    }else{
        $bc_tail_file = "";
    }
}

// 5. 게시판 저장
$sql = "insert into bd__board_config set
        bc_code = '".trim($_POST[bc_code])."',
        bc_name = '".trim($_POST[bc_name])."',
        bc_head_file = '".$bc_head_file."',
        bc_head = '".$_POST[b_title]."',
        bc_tail_file = '".$bc_tail_file."',
        bc_tail = '".$_POST[bc_tail]."',
        bc_list_level = '".$_POST[bc_list_level]."',
        bc_read_level = '".$_POST[bc_read_level]."',
        bc_write_level = '".$_POST[bc_write_level]."',
        bc_reply_level = '".$_POST[bc_reply_level]."',
        bc_comment_level = '".$_POST[bc_comment_level]."',
        bc_admin = '".$_POST[bc_admin]."',
        bc_use_file = '".$_POST[bc_use_file]."',
        bc_use_secret = '".$_POST[bc_use_secret]."',
        bc_use_reply = '".$_POST[bc_use_reply]."',
        bc_use_comment = '".$_POST[bc_use_comment]."'
        ";
sql_query($sql);


// 6. 저장된 게시판번호 찾기
$bc_idx = mysql_insert_id();

// 7. 파일저장
if($_FILES[bc_head_file][tmp_name] && $bc_head_file){
    if(file_exists($head_file)){
        @unlink($head_file);
    }
    move_uploaded_file($_FILES[bc_head_file][tmp_name], $head_file);
    chmod($head_file, 0666);

}
if($_FILES[bc_tail_file][tmp_name] && $bc_tail_file){
    if(file_exists($tail_file)){
        @unlink($tail_file);
    }
    move_uploaded_file($_FILES[bc_tail_file][tmp_name], $tail_file);
    chmod($tail_file, 0666);

}

// 8. 게시판목록 페이지로 보내기
alert("게시판이 생성 되었습니다.", "./admin_board_list.php");
?>
