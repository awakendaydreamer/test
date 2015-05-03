<!DOCTYPE html>
<?
//######################################
//
//  설치실행파일 : install/install.php
//
//######################################
// 1. db.php 파일의 존재유무로 설치 했는지 확인 (존재하면 설치한것임)
if(file_exists("../db.php")){
    ?>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <script>
    alert("이미 설치가 되어 있습니다.");
    location.replace("../index.php");
    </script>
    <?
    exit;
}

// 2. 게시판의 최상단 디렉토리가 쓰기가능인지 검사
if (!is_writeable(".."))
{
    ?>
    <html>
    <head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <title></title>
    </head>
    <script>
        alert("최상위 디렉토리의 퍼미션을 707 이나 777 로 변경하여 주세요.");
    </script>
    <body>
    최상위 디렉토리의 퍼미션을 707 이나 777 로 변경하여 주세요.
    </body>
    </html>
    <?
    exit;
}

// 3. 변수정리
$host = trim($_POST[host]);
$user = trim($_POST[user]);
$pass = trim($_POST[pass]);
$db_name = trim($_POST[db_name]);

$admin_id = trim($_POST[admin_id]);
$admin_name = trim($_POST[admin_name]);
$admin_pass = trim($_POST[admin_pass]);

// 4. 에러시 메세지 내보낼 함수
function echo_message($msg)
{
    echo '<meta http-equiv="Content-Type" content="text/html" charset="UTF-8">';
    echo '<script>';
    echo 'alert("'.$msg.'");';
    echo 'history.back();';
    echo '</script>';
}

// 5. DB 접속
$connect = @mysql_connect($host, $user, $pass);
if(!$connect){
    echo_message("MySql 호스트명, 사용자ID, 비밀번호를 확인해 주십시오.");
    exit;
}

$select_db = @mysql_select_db($db_name, $connect);
if(!$connect){
    echo_message("MySql DB명을 확인해 주십시오.");
    exit;
}

// 6. 테이블 만들기

// 회원 테이블 만들기
$sql = "
DROP TABLE IF EXISTS `bd__member`;
";
mysql_query($sql);
$sql = "
CREATE TABLE `bd__member` (
  `m_idx` int(11) NOT NULL auto_increment,
  `m_id` varchar(12) NOT NULL,
  `m_name` varchar(10) NOT NULL,
  `m_pass` varchar(100) NOT NULL,
  `m_level` tinyint(2) NOT NULL default '1',
  PRIMARY KEY  (`m_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=euckr AUTO_INCREMENT=1 ;
";
$result1 = mysql_query($sql, $connect);

// 게시판 설정 테이블 만들기
$sql = "
DROP TABLE IF EXISTS `bd__board_config`;
";
mysql_query($sql);
$sql = "
CREATE TABLE `bd__board_config` (
  `bc_idx` int(11) NOT NULL auto_increment,
  `bc_code` varchar(50) NOT NULL,
  `bc_name` varchar(50) NOT NULL,
  `bc_head_file` varchar(255) NOT NULL,
  `bc_head` text NOT NULL,
  `bc_tail_file` varchar(255) NOT NULL,
  `bc_tail` text NOT NULL,
  `bc_list_level` tinyint(2) NOT NULL default '0',
  `bc_read_level` tinyint(2) NOT NULL default '0',
  `bc_write_level` tinyint(2) NOT NULL default '0',
  `bc_reply_level` tinyint(2) NOT NULL default '0',
  `bc_comment_level` tinyint(2) NOT NULL default '0',
  `bc_admin` text NOT NULL,
  `bc_use_file` tinyint(2) NOT NULL default '0',
  `bc_use_secret` tinyint(2) NOT NULL default '0',
  `bc_use_reply` tinyint(2) NOT NULL default '0',
  `bc_use_comment` tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (`bc_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=euckr AUTO_INCREMENT=1 ;
";
$result2 = mysql_query($sql, $connect);

// 게시판 글 테이블 만들기
$sql = "
DROP TABLE IF EXISTS `bd__board`;
";
mysql_query($sql);
$sql =
"CREATE TABLE `bd__board` (
  `b_idx` int(11) NOT NULL auto_increment,
  `bc_code` varchar(50) NOT NULL,
  `b_num` int(11) NOT NULL,
  `b_reply` varchar(3) NOT NULL,
  `m_id` varchar(12) NOT NULL,
  `m_name` varchar(10) NOT NULL,
  `b_pass` varchar(255) NOT NULL,
  `b_title` varchar(255) NOT NULL,
  `b_contents` text NOT NULL,
  `b_is_secret` tinyint(2) NOT NULL default '0',
  `b_filename` varchar(255) NOT NULL,
  `b_filesize` int(11) NOT NULL default '0',
  `b_cnt` int(11) NOT NULL default '0',
  `b_regdate` datetime NOT NULL,
  PRIMARY KEY  (`b_idx`),
  KEY `board_order` (`b_num`,`b_reply`),
  KEY `bc_code` (`bc_code`)
) ENGINE=MyISAM DEFAULT CHARSET=euckr AUTO_INCREMENT=1 ;
";
$result3 = mysql_query($sql, $connect);

// 댓글 테이블 만들기
$sql = "
DROP TABLE IF EXISTS `bd__comment`;
";
mysql_query($sql);
$sql = "
CREATE TABLE `bd__comment` (
  `co_idx` int(11) NOT NULL auto_increment,
  `b_idx` int(11) NOT NULL,
  `m_id` varchar(12) NOT NULL,
  `m_name` varchar(10) NOT NULL,
  `co_pass` varchar(255) NOT NULL,
  `co_contents` text NOT NULL,
  `co_regdate` datetime NOT NULL,
  PRIMARY KEY  (`co_idx`),
  KEY `b_idx` (`b_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=euckr AUTO_INCREMENT=1 ;
";
$result4 = mysql_query($sql, $connect);

// 조회수용 글 읽기 히스토리 테이블 만들기
$sql = "
DROP TABLE IF EXISTS `bd__view_history`;
";
mysql_query($sql);
$sql = "
CREATE TABLE `bd__view_history` (
  `vh_idx` int(11) NOT NULL auto_increment,
  `b_idx` int(11) NOT NULL,
  `m_id` varchar(12) NOT NULL,
  `m_ip` varchar(15) NOT NULL,
  PRIMARY KEY  (`vh_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=euckr AUTO_INCREMENT=1 ;
";
$result5 = mysql_query($sql, $connect);

// 7. 테이블이 다 만들어졌는지 검사
if(!$result1 || !$result2 || !$result3 || !$result4 || !$result5){
    echo_message("테이블 생성에 실패하였습니다.");
    exit;
}


// 8. 운영자 회원테이블에 적기
$sql = "insert into bd__member set m_id = '".$admin_id."', m_name = '".$admin_name."', m_pass = '".$admin_pass."', m_level = '9' ";
$result6 = mysql_query($sql, $connect);

// 9. 운영자 정보가 회원테이블에 적혔는지 검사
if(!$result6){
    echo_message("운영자 정보를 적는데 실패하였습니다.");
    exit;
}

// 10. DB 설정 파일 생성
$file = "../db.php";
$fp = @fopen($file, "w");

fwrite($fp, "<?\n");
fwrite($fp, "\$mysql_host = '$host';\n");
fwrite($fp, "\$mysql_user = '$user';\n");
fwrite($fp, "\$mysql_password = '$pass';\n");
fwrite($fp, "\$mysql_db = '$db_name';\n");
fwrite($fp, "?>");

fclose($fp);
@chmod($file, 0606);

// 11. 첨부파일 저장할 디렉토리 생성
@mkdir("../data", 0707);
@chmod("../data", 0707);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
<title>게시판 설치</title>
</head>
<body>
설치가 완료 되었습니다.
<a href="../index.php">첫화면으로 가기</a>
</body>
</html>
