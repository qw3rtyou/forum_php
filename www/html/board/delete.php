<?php	
session_start();
$uid=$_SESSION['userid'];
if (!isset($uid)) {
	header('Location: ../login/login.php');
	exit();
}
require_once('../../config/login_config.php');
require_once('../../config/input_config.php');
$bno = sqli_checker($conn, $_GET['bno']);
$sql = mysqli_query($conn, "select name from board where idx='$bno';");
$board = $sql->fetch_array();
if($uid==$board['name']){
	mysqli_query($conn, "delete from board where idx='$bno';");
	mysqli_query($conn, "delete from reply where post_id='$bno';");
	mysqli_query($conn, "delete from `file` where post_id='$bno';");
	echo "<script>location.href='./board.php';</script>";
}else{
	echo "<script type='text/javascript'>alert('Authenticationfailed.. :(');history.back();</script>";
}?>	
