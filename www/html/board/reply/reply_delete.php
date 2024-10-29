<?php
require_once('../../../config/login_config.php');
require_once('../../../config/input_config.php');
$rno = sqli_checker($conn, $_GET['rno']);

session_start();
$uid=$_SESSION['userid'];
if(!isset($uid)) {
	header('Location: ../login/login.php');
	exit();
}

if(!isset($rno)){
	header('Location: ../board/board.php');
	exit();
}

$sql = mysqli_query($conn, "select name from reply where idx='".$rno."'");
$reply = $sql->fetch_array();

if($uid==$reply['name']){
	$sql = mysqli_query($conn, "delete from reply where idx='".$rno."'"); ?>
	<script type="text/javascript"> location.replace(document.referrer);</script>
	<?php
	$sql = mysqli_query($conn,"ALTER TABLE reply AUTO_INCREMENT=1");
	$sql = mysqli_query($conn,"SET @COUNT = 0");
	$sql = mysqli_query($conn,"UPDATE reply SET reply.idx = @COUNT:=@COUNT+1");
	?>
<?php 
}else{ ?>
	<script type="text/javascript">alert('Authentication failed.. :(');history.back();</script>
<?php } ?>
