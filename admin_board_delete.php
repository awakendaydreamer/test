<!DOCTYPE html>
<?
// 1. 공통 인클루드 파일
include "./admin_head.php";

// 2. 게시판 존재여부 검사
$sql = "select * from bd__board_config where bc_idx = '".$_GET[bc_idx]."'";
$data = sql_fetch($sql);
if(!$data[bc_idx]){
    alert("없는 게시판입니다.");
}

$bc_idx = $_GET[bc_idx];

$dir = "./data/board_config";
$head_file = $dir."/".$bc_idx."_head";
$tail_file = $dir."/".$bc_idx."_tail";

// 3. 파일 삭제
@unlink($head_file);
@unlink($tail_file);

// 4. 게시판 삭제
$sql = "delete from bd__board_config where bc_idx = '".$bc_idx."'";
sql_query($sql);

// 5. 코멘트 및 파일 삭제를 위한 게시글 목록 구하기
$sql = "select * from bd__board where bc_code = '".$data[bc_code]."'";
$data1 = sql_list($sql);

// 6. 게시글 삭제
$sql = "delete from bd__board where bc_code = '".$data[bc_code]."'";
sql_query($sql);

// 7. 코멘트 및 게시물 파일 삭제
for($i=0;$i<count($data1);$i++){
    $sql = "delete from bd__comment where b_idx = '".$data1[b_idx]."'";
    sql_query($sql);

    $b_file = "./data/".$data1[b_idx];
    @unlink($b_file);
}

// 8. 게시판목록 페이지로 보내기
alert("게시판이 삭제 되었습니다.", "./admin_board_list.php");
?>
