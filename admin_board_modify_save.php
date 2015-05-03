<!DOCTYPE html>
<?
// 1. 공통 인클루드 파일
include "./admin_head.php";

// 2. 게시판 존재여부 검사
$sql = "select * from bd__board_config where bc_idx = '".$_POST[bc_idx]."'";
$data = sql_fetch($sql);
if(!$data[bc_idx]){
    alert("없는 게시판입니다.");
}

// 3. 넘어온 변수 검사
if(trim($_POST[bc_code]) == ""){
    alert("게시판코드를 입력해 주세요.");
}

$sql = "select * from bd__board_config where bc_code = '".trim($_POST[bc_code])."' and bc_idx != '".$_POST[bc_idx]."'";
$data1 = sql_fetch($sql);
if($data1[bc_idx]){
    alert("이미 존재하는 게시판입니다.");
}

if(trim($_POST[bc_name]) == ""){
    alert("게시판이름을 입력해 주세요.");
}

$bc_idx = $_POST[bc_idx];

$dir = "./data/board_config";
$head_file = $dir."/".$bc_idx."_head";
$tail_file = $dir."/".$bc_idx."_tail";

$bc_head_file = $data[bc_head_file];
$bc_tail_file = $data[bc_tail_file];

// 4. 파일 삭제 체크시 삭제
if($_POST[bc_head_file_del]){
    @unlink($head_file);
    $bc_head_file = "";
}
if($_POST[bc_tail_file_del]){
    @unlink($tail_file);
    $bc_tail_file = "";
}

// 5. 파일이 jpg나 gif 인지 검사
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

// 7. 게시판 저장
$sql = "update bd__board_config set
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
        where bc_idx = '".$bc_idx."'
        ";
sql_query($sql);


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

// 8. 코드가 바귀었으면 게시판 글의 코드 수정
if($data[bc_code] != trim($_POST[bc_code])){
    $sql = "update bd__board set bc_code ='".trim($_POST[bc_code])."' where bc_code = '".$data[bc_code]."'";
    sql_query($sql);
}

// 9. 게시판목록 페이지로 보내기
alert("게시판이 수정 되었습니다.", "./admin_board_list.php");
?>
