<!DOCTYPE html>
<?
// 1. 공통 인클루드 파일
include "./admin_head.php";

// 2. 회원 존재여부 검사
$sql = "select * from bd__member where m_idx = '".$_POST[m_idx]."'";
$data = sql_fetch($sql);
if(!$data[m_idx]){
    alert("없는 회원입니다.");
}

// 3. 넘어온 변수 검사
if(trim($_POST[m_name]) == ""){
    alert("회원이름을 입력해 주세요.");
}

$m_idx = $_POST[m_idx];

// 4. 회원 저장
$sql = "update bd__member set
        m_name = '".trim($_POST[m_name])."',
        m_level = '".$_POST[m_level]."'
        where m_idx = '".$m_idx."'
        ";
sql_query($sql);


// 5. 이름이 바귀었으면 회원 글과 코멘트의 이름 수정
if($data[m_name] != trim($_POST[m_name])){
    $sql = "update bd__board set m_name ='".trim($_POST[m_name])."' where m_id = '".$data[m_id]."'";
    sql_query($sql);

    $sql = "update bd__comment set m_name ='".trim($_POST[m_name])."' where m_id = '".$data[m_id]."'";
    sql_query($sql);
}


// 6. 회원목록 페이지로 보내기
alert("회원이 수정 되었습니다.", "./admin_member_list.php");
?>
