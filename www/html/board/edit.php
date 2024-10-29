<?php
session_start();
$uid = $_SESSION['userid'];
if (!isset($uid)) {
  header('Location: ../login/login.php');
  exit();
}

require_once('../../config/login_config.php');
require_once('../../config/input_config.php');
$bno = sqli_checker($conn, $_GET['bno']);

$sql = mysqli_query($conn, "SELECT name, title, content FROM board WHERE idx='$bno';");
$board = $sql->fetch_array();
$name = $board['name'];
$title = $board['title'];
$content = $board['content'];

if ($name != $uid) {
  echo "<script>alert('Authentication err.. :(');history.back();</script>";
  exit;
}

$result = mysqli_query($conn, "SELECT file_name FROM `file` WHERE post_id='$bno'");
$fileNames = array();
while ($row = mysqli_fetch_array($result)) {
  $fileNames[] = $row['file_name'];
}
?>

<html>
	<head>
		<link rel="stylesheet" href="../style/style_write.css">
		<meta charset="UTF-8">
		<title>board</title>
		<script>
      function handleFileSelect(event) {
        var input = event.target;
        var fileNameDisplay = document.getElementById("file-name-display");
        fileNameDisplay.innerHTML = "";
        
        for (var i = 0; i < input.files.length; i++) {
          var fileName = input.files[i].name;
          var span = document.createElement("span");
          span.textContent = fileName;
          fileNameDisplay.appendChild(span);
        }
      }
      
      window.addEventListener('DOMContentLoaded', function() {
        var fileNameDisplay = document.getElementById("file-name-display");
        fileNameDisplay.innerHTML = "";

        <?php foreach ($fileNames as $fileName) { ?>
          var span = document.createElement("span");
          span.textContent = "<?php echo addslashes($fileName); ?>";
          fileNameDisplay.appendChild(span);
        <?php } ?>
      });
    </script>

	</head>
	<body>
		<div id="board_write">
			<div id="container-back">
				<h4><a href="board.php">←Back</a></h4>
			</div>
		  <h4>Upload posting</h4>
		  <div id="write_area">
		    <form action="edit_ok.php" method="post" enctype="multipart/form-data">
		      <hr>
		      <div id="in_file">
            <label for="file-upload" class="custom-file-upload">Upload File</label>
            <input type="file" id="file-upload" name="b_file[]" multiple="multiple" style="display:none;" onchange="handleFileSelect(event)">
            <input id="bno" name="bno" style="display:none" value=<?php echo $bno?>>
			<span class="file-name" id="file-name-display"></span>
					</div>
				  <div id="in_title">
				    <textarea name="title" id="utitle" rows="1" cols="55" placeholder="Title" maxlength="100" required><?php echo $title; ?></textarea>
				  </div>
				  <div id="in_content">
				    <textarea name="content" id="ucontent" placeholder="Content" required><?php echo $content; ?></textarea>
				  </div>
				  <div id="in_submit">
				  	<button type="submit">Go!</button>
				  </div>
		    </form>
		  </div>
		</div>
	</body>
</html>
