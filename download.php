<!DOCTYPE html>
<?
// 1. 공통 인클루드
include "./inc/config.php";

// 2. 출력 헤더 만들기
$alert_header = "
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=euc-kr'>
<title></title>
</head>
";

// 3. 게시판 코드 검사
$bc_code = $_GET[bc_code];
if($bc_code){
    // 3. 게시판 코드가 있으면 게시판 설정 불러오기
    $b_config_sql = "select * from ".$_cfg['config_table']." where bc_code = '".$bc_code."'";
    $board_config = sql_fetch($b_config_sql);
}else{
    echo $alert_header;
    alert("게시판 코드가 없습니다.");
}

// 4. 존재하는 게시판인지 확인
if(!$board_config[bc_idx]){
    echo $alert_header;
    alert("존재 하지 않는 게시판입니다.");
}

// 5. 게시판 권한 체크
if($_SESSION[user_level]){
    $u_level = $_SESSION[user_level];
}else{
    $u_level = 0;
}

if($u_level < $board_config[bc_read_level]){
    echo $alert_header;
    alert("권한이 없습니다.", "./index.php");
}

// 6. 글정보 가져오기
$b_idx = $_GET[b_idx];
$sql = "select * from ".$_cfg['board_table']." where bc_code = '".$bc_code."' and b_idx = '".$b_idx."'";
$data = sql_fetch($sql);

// 7. 해당 글이 있는지 와 비밀글이면 비밀번호 입력여부 체크체크
if(!$data[b_idx]){
    echo $alert_header;
    alert("존재 하지 않는 글입니다.");
}

if($data[b_is_secret] && !$_SESSION["b_pass_".$b_idx] && $_SESSION[user_id] != $data[m_id] && $u_level != 9){
    echo $alert_header;
    alert("비밀번호를 입력하여 주세요.");
}

// 8. 파일이 등록되어 있는지 검사
if(!$data[b_filename]){
    echo $alert_header;
    alert("파일이 존재 하지 않습니다.");
}

// 9. 파일 이름과 실제 파일
$dir = "./data";
$file_path = $dir."/".$b_idx;
$original = $data[b_filename];

// 10.파일이 있으면
if (file_exists($file_path)) {

    // 11. 다운로드 헤더 만들기

    if(eregi("(MSIE 5.0|MSIE 5.1|MSIE 5.5|MSIE 6.0)", $HTTP_USER_AGENT))
    {
      if(strstr($HTTP_USER_AGENT, "MSIE 5.5"))
      {
        header("Content-Type: doesn/matter");
        header("Content-disposition: filename=$original");
        header("Content-Transfer-Encoding: binary");
        header("Pragma: no-cache");
        header("Expires: 0");
      }

      if(strstr($HTTP_USER_AGENT, "MSIE 5.0"))
      {
        Header("Content-type: file/unknown");
        header("Content-Disposition: attachment; filename=$original");
        Header("Content-Description: PHP3 Generated Data");
        header("Pragma: no-cache");
        header("Expires: 0");
      }

      if(strstr($HTTP_USER_AGENT, "MSIE 5.1"))
      {
        Header("Content-type: file/unknown");
        header("Content-Disposition: attachment; filename=$original");
        Header("Content-Description: PHP3 Generated Data");
        header("Pragma: no-cache");
        header("Expires: 0");
      }

      if(strstr($HTTP_USER_AGENT, "MSIE 6.0"))
      {
        Header("Content-type: application/x-msdownload");
        Header("Content-Length: ".(string)(filesize("$file_path")));
        Header("Content-Disposition: attachment; filename=$original");
        Header("Content-Transfer-Encoding: binary");
        Header("Pragma: no-cache");
        Header("Expires: 0");

      }


    } else {
      Header("Content-type: doesn/matter");
      Header("Content-Length: ".(string)(filesize("$file_path")));
      Header("Content-Disposition: attachment; filename=$original");
      Header("Content-Description: PHP3 Generated Data");
      Header("Pragma: no-cache");
      Header("Expires: 0");
    }
    flush();

    // 12. 파일을 읽어 내보내기
    if (is_file($file_path)) {
        $fp = fopen($file_path, "rb");

        while(!feof($fp)) {
            echo fread($fp, 100*1024);
            flush();
        }
        fclose ($fp);
        flush();
    }


} else {
    echo $alert_header;
    alert("파일이 존재 하지 않습니다.");
}
?>
