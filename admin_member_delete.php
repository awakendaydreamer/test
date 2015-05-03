<!DOCTYPE html>
<?
// 1. 공통 인클루드 파일
include "./admin_head.php";


// 2. 회원 존재여부 검사
$sql = "select * from bd__member where m_idx = '".$_GET[m_idx]."'";
$data = sql_fetch($sql);
if(!$data[m_idx]){
    alert("없는 회원입니다.");
}

$m_idx = $_GET[m_idx];

// 4. 회원 삭제
$sql = "delete from bd__member where m_idx = '".$m_idx."'";
sql_query($sql);

// 5. 글에 딸린 코멘트 및 파일 삭제를 위한 게시글 목록 구하기
$sql = "select * from bd__board where m_id = '".$data[m_id]."'";
$data1 = sql_list($sql);

// 6. 게시글 삭제
$sql = "delete from bd__board where m_id = '".$data[m_id]."'";
sql_query($sql);

// 7. 게시글에 딸린 코멘트 및 게시물 파일 삭제
for($i=0;$i<count($data1);$i++){
    $sql = "delete from bd__comment where b_idx = '".$data1[b_idx]."'";
    sql_query($sql);

    $b_file = "./data/".$data1[b_idx];
    @unlink($b_file);
}

// 8. 코멘트 삭제
$sql = "delete from bd__comment where m_id = '".$data1[m_id]."'";
sql_query($sql);

// 9. 회원목록 페이지로 보내기
alert("회원이 삭제 되었습니다.", "./admin_member_list.php");
?>
