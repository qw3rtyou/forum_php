<?php
session_start();
$username = $_SESSION['userid'];
if (!isset($username)) {
	header('Location: ../login/login.php');
	exit();
}
require_once ('../../config/login_config.php');
require_once ('../../config/input_config.php');
//$title = sqli_checker($conn, $_POST['title']);
//$content = sqli_checker($conn, $_POST['content']);
$title = $_POST['title'];
$content = $_POST['content'];

$maxfilesize = 1000000;
$upload_count = count($_FILES['b_file']['name']);

// if (input_check($content) || input_check($title)) {
// 	echo "<script>
// 		alert('Attack Detected! :(');
// 		history.back();
// 	</script>"; 
// 	exit;      
// }

if ($upload_count > 5) {
	echo "<script>
		alert('File can only be uploaded up to 5 at a time.. :(');
		history.back();
	</script>";
	exit;
}

// $allowedFileTypes = array(
//     "image/jpeg",
//     "image/png",
//     "image/gif",
//     "text/plain",
//     "application/pdf"
// );
// $uploadedFileType = $_FILES['b_file']['type'][$i];

// if (!in_array($uploadedFileType, $allowedFileTypes)) {
//     echo "<script>
//         alert('Unsupported file type.. :(');
//         history.back();
//     </script>"; 
//     exit;
// }

for ($i = 0; $i < $upload_count; $i++) {
	$filename = isset($_FILES['b_file']['name'][$i]) ? $_FILES['b_file']['name'][$i] : null;
	$filename = sqli_checker($conn, $filename);

	$ex = explode(".", strtolower($filename), 2);

	if (strlen($ex[0]) === 20) {
		echo "<script>
			alert('($i+1)+'st file name so long.. :(');
			history.back();
		</script>";
		exit;
	}

	if (!($ex[1] == null) && !($ex[1] == "txt" || $ex[1] == "png" || $ex[1] == "jpg")) {
		echo "<script>
			alert('($i+1)+'st file unsupported extension.. :(');
			history.back();
		</script>";
		exit;
	}

	if ($_FILES['b_file']['size'][$i] > $maxfilesize) {
		echo "<script>
			alert(($i+1)+'st file has exceeded the maximum file size limit.. :(');
			history.back();
		</script>";
		exit;
	}
}

require_once ('../../config/login_config.php');
mysqli_query($conn, "INSERT INTO board (name, title, content, date, hit) VALUES ('$username', '$title', '$content', NOW(), 0)");

$sql = mysqli_query($conn, "select max(idx) from board");
$sql2 = mysqli_fetch_row($sql);
$board_num = (int) $sql2[0];

for ($i = 0; $i < $upload_count; $i++) {
	$tmpfile = $_FILES['b_file']['tmp_name'][$i];
	$filename = $_FILES['b_file']['name'][$i];
	$filename = sqli_checker($conn, $filename);

	$folder = "../upload/" . $filename;
	move_uploaded_file($tmpfile, $folder);
	mysqli_query($conn, "insert into file(idx, post_id, file_name) values(0,'$board_num','$filename')");
}
?>
<script type="text/javascript">location.href = "board.php";</script>