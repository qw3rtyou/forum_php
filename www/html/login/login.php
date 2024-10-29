<?php
session_start();
require_once ('../../config/login_config.php');
require_once ('../../config/input_config.php');
if (isset($_POST['userid']) && isset($_POST['userpw'])) {
    $userid = sqli_checker($conn, $_POST['userid']);
    $userpw = sqli_checker($conn, $_POST['userpw']);
    $sql = "SELECT * FROM login WHERE login_id='$userid'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $hashedPassword = $row['login_pw'];
        if (password_verify($userpw, $hashedPassword)) {
            $_SESSION['userid'] = $userid;
            header('Location: ../board/board.php');
            exit;
        }
    }
    $message = "Login err..";
}
?>
<!DOCTYPE html>
<html lang="kr">

<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="../style/style_login.css">

</head>

<body>
    <form method="post">
        <h1>Login</h1>
        <fieldset>
            <table>
                <tr>
                    <td>ID</td>
                    <td><input type="text" size="35" name="userid" placeholder="id"></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type="password" size="35" name="userpw" placeholder="pw"></td>
                </tr>
            </table>

            <input type="submit" value="Sign in" onclick="location.href='sign_up_ok.php'"></input>
            <button type="button" onclick="location.href='sign_up.php'">Sign up</button>
        </fieldset>
    </form>
    <div class="message">
        <?php if (isset($message))
            echo $message; ?>
    </div>
</body>

</html>