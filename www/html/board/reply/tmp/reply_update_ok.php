<?php
require_once('../../../config/login_config.php');
require_once('../../../config/input_config.php');
$rno = sqli_checker($conn, $_GET['rno']);
$bno = sqli_checker($conn, $_GET['bno']);
$pwk = sqli_checker($conn, $_GET['pw']);

$stmt1 = $conn->prepare("SELECT * FROM reply WHERE idx = ?");
$stmt1->bind_param("i", $rno);
$stmt1->execute();
$reply = $stmt1->get_result()->fetch_array();
$stmt1->close();

$stmt2 = $conn->prepare("SELECT * FROM board WHERE idx = ?");
$stmt2->bind_param("i", $bno);
$stmt2->execute();
$board = $stmt2->get_result()->fetch_array();
$stmt2->close();

$bpw = $reply['pw'];

if ($pwk === $bpw) {
    $stmt3 = $conn->prepare("UPDATE reply SET content = ? WHERE idx = ?");
    $stmt3->bind_param("si", $_POST['content'], $rno);
    $stmt3->execute();
    $stmt3->close();
?>
<script type="text/javascript">alert('수정되었습니다.'); location.replace("../read.php?idx=<?php echo $bno; ?>");</script>
<?php
} else {
?>
<script type="text/javascript">alert('비밀번호가 틀립니다');history.back();</script>
<?php
}
?>
