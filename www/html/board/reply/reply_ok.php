<?php
require_once('../../../config/login_config.php');
require_once('../../../config/input_config.php');
$bno = sqli_checker($conn, $_GET['idx']);
$content = sqli_checker($conn, $_POST['content']);
session_start();
$uid = $_SESSION['userid'];

if (!$uid) {
    header('Location: ../login/login.php');
    exit();
}

if ($bno && $content) {
    $stmt = $conn->prepare("INSERT INTO reply (post_id, name, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $bno, $uid, $content);
    $stmt->execute();
    $stmt->close();

    echo "<script>location.href='../read.php?idx=$bno';</script>";
} else {
    echo "<script>alert('Something\'s wrong.. :('); 
    history.back();</script>";
}
?>
