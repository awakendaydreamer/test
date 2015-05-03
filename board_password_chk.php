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

if($u_level < $board_config[bc_read_level]){
    alert("권한이 없습니다.", "./index.php");
}

// 6. 페이징 변수 설정
if($_POST[page] && $_POST[page] > 0){
    // 현재 페이지 값이 존재하고 0 보다 크면 그대로 사용
    $page = $_POST[page];
}else{
    // 그 외의 경우는 현재 페이지를 1로 설정
    $page = 1;
}

// 7. 글정보 가져오기
$b_idx = $_POST[b_idx];
$sql = "select * from ".$_cfg['board_table']." where bc_code = '".$bc_code."' and b_idx = '".$b_idx."'";
$data = sql_fetch($sql);

// 8. 해당 글이 있는지체크
if(!$data[b_idx]){
    alert("존재 하지 않는 글입니다.");
}

// 9. 비밀번호가 맞는지 체크
if($data[b_pass] == $_POST[b_pass]){
    // 10. 맞으면 해당글의 비밀번호 체크 여부를 세션에 저장하고 이동
    $_SESSION["b_pass_".$b_idx] = true;
    goto_url("./board_view.php?bc_code=".$bc_code."&b_idx=".$b_idx."&page=".$_POST[page]);
}else{
    alert("비밀번호가 다릅니다.");
}

?>
