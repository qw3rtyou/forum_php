<?php
require_once ('mysql_info.php');

try {
    $conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);
} catch (Exception $e) {
    echo "DB 컨테이너 연결 중..!";
}
?>