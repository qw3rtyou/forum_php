<?php
require_once('../../config/login_config.php');

for ($i = 1; $i <= 1000; $i++) {
    $title = "Dummy post$i";
    $content = "Dummy post content$i";
    
    $sql = "INSERT INTO board (title, name, content, date, hit) VALUES ('$title', 'admin', '$content', NOW(), 0)";
    mysqli_query($conn, $sql);
}

echo '<script> history.go(-1);</script>';
?>

