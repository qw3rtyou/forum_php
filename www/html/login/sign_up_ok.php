<?php
require_once ('../../config/login_config.php');
require_once ('../../config/input_config.php');
$userid = sqli_checker($conn, $_POST['userid']);
$userpw = sqli_checker($conn, $_POST['userpw']);

$checkQuery = "SELECT * FROM login WHERE login_id='$userid'";
$result = mysqli_query($conn, $checkQuery);

if (mysqli_num_rows($result) > 0) {
    echo '<meta charset="utf-8" />';
    echo '<script type="text/javascript">alert("Duplicate ID. Please use another ID.");</script>';
    echo '<meta http-equiv="refresh" content="0 url=/login/sign_up.php">';
    exit;
}

$hashedPassword = password_hash($userpw, PASSWORD_DEFAULT);

$insertQuery = "INSERT INTO login (id, login_id, login_pw, created) VALUES ('0', '$userid', '$hashedPassword', NOW())";
mysqli_query($conn, $insertQuery);

mysqli_close($conn);

echo '<meta charset="utf-8" />';
echo '<script type="text/javascript">alert("Account creation completed!\nWelcome ' . $userid . ' :)");</script>';
echo '<meta http-equiv="refresh" content="0 url=/login/login.php">';
?>