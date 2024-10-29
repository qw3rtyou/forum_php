<?php
session_start();
if (!isset($_SESSION['userid'])) {
  header('Location: ../login/login.php');
  exit();
}
?>

<!doctype html>
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
  </script>
</head>
<body>
  <div id="board_write">
  	<div id="container-back">
  		<h4><a href="javascript:history.go(-1)">←Back</a></h4>
  	</div>
    <h4>Upload posting</h4>
    <div id="write_area">
      <form action="write_ok.php" method="post" enctype="multipart/form-data">
        <hr>
        <div id="in_file">
			<label for="file-upload" class="custom-file-upload">Upload File</label>
			<input type="file" id="file-upload" name="b_file[]" multiple="multiple" style="display:none;" onchange="handleFileSelect(event)">
			<span class="file-name" id="file-name-display"></span>
		</div>
		<div id="in_title">
			<textarea name="title" id="utitle" rows="1" cols="55" placeholder="Title" maxlength="100" required></textarea>
		</div>
		<div id="in_content">
			<textarea name="content" id="ucontent" placeholder="Content" required></textarea>
		</div>
		<div id="in_submit">
			<button type="submit">Go!</button>
		</div>
      </form>
    </div>
  </div>
</body>
</html>
