<!doctype html>
<head>
    <meta charset="UTF-8">
    <title>reply-edit</title>
</head>
<body>
  <?php
    require_once('../../../config/login_config.php');
    require_once('../../../config/input_config.php');
    $rno = sqli_checker($conn, $_GET['rno']);
		$sql = mysqli_query($conn, "select * from reply where idx='$rno'");
		$reply = $sql->fetch_array();
	?>
  <div>
    <form method="post" action="reply_modify_ok.php">
      <input type="hidden" name="rno" value="<?php echo $rno; ?>"/>
      <textarea name="content"><?php echo $reply["content"]; ?></textarea>
      <input type="submit" value="Edit">
    </form>
  </div>
</body>
</html>

<?php
// Assuming you have the necessary database connection setup.
// You can retrieve the POST data sent by AJAX.
$data = json_decode(file_get_contents("php://input"), true);

if ($data && isset($data['replyId']) && isset($data['editedContent'])) {
    $replyId = $data['replyId'];
    $editedContent = $data['editedContent'];

    // Use the $replyId and $editedContent to update the reply in the database.
    // For example:
    // 1. Perform necessary validation and sanitization on $editedContent.
    // 2. Use prepared statements or proper database escaping to avoid SQL injection.
    // 3. Execute the UPDATE query to update the reply in the database.

    // After the database update, you can send a response back to the client.
    // For this example, we'll assume the update is successful and send a success response.
    $response = array('success' => true);
    echo json_encode($response);
} else {
    // If the request data is invalid or missing, send an error response.
    $response = array('success' => false);
    echo json_encode($response);
}
?>