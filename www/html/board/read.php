<?php
session_start();

if (!isset($_SESSION['userid'])) {
	header('Location: ../login/login.php');
	exit();
}
?>

<!doctype html>

<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="../style/style_read.css">
	<title>post</title>
</head>

<body>
	<script>
		document.addEventListener("DOMContentLoaded", function () {
			const editReplyForms = document.querySelectorAll(".reply_view");

			editReplyForms.forEach(form => {
				const replyId = form.getAttribute("data-reply-id");//help!
				const replyContent = form.querySelector(".edit-reply-content");
				const cancelButton = form.querySelector(".button-cancel-edit");
				const saveButton = form.querySelector(".button-save-edit");
				const editButton = form.querySelector(".button-edit-reply");
				const deleteButton = form.querySelector("a.button-delete");

				const enableEdit = () => {
					replyContent.disabled = false;
					cancelButton.style.display = "inline-block";
					saveButton.style.display = "inline-block";

					editButton.style.display = "none";
					deleteButton.style.display = "none";
				};

				const disableEdit = () => {
					replyContent.disabled = true;
					cancelButton.style.display = "none";
					saveButton.style.display = "none";

					editButton.style.display = "inline-block";
					deleteButton.style.display = "inline-block";
				};

				disableEdit();

				form.querySelector(".button-edit-reply").addEventListener("click", () => {
					enableEdit();
				});

				cancelButton.addEventListener("click", (e) => {
					e.preventDefault();
					disableEdit();
				});

				saveButton.addEventListener("click", (e) => {
					e.preventDefault();
					const editedContent = replyContent.value;

					fetch("reply/reply_update.php", {
						method: "POST",
						body: JSON.stringify({ replyId: replyId, editedContent: editedContent }),
						headers: {
							"Content-Type": "application/json"
						}
					})
						.then(response => response.json())
						.then(data => {
							if (data.success) {
								disableEdit();
							} else {
								alert("res err..");
							}
						})
						.catch(error => {
							alert("axios err..");
						});
				});
			});
		});
	</script>
	<div id="nav">
		<?php
		require_once ('../../config/input_config.php');
		$uid = $_SESSION['userid'];
		echo '<a href="../login/logout.php">Logout :(</a>';
		echo '<a id="hello-name">ID : ' . $uid . '</a>';
		?>
	</div>
	<div id="wrapper-full">
		<h4><a href="board.php">←Back</a></h4>
		<?php
		require_once ('../../config/login_config.php');
		$bno = sqli_checker($conn, $_GET['idx']);
		$hit = mysqli_fetch_array(mysqli_query($conn, "select * from board where idx ='$bno'"));
		$hit = $hit['hit'] + 1;
		$fet = mysqli_query($conn, "update board set hit = '$hit' where idx = '$bno'");
		$sql = mysqli_query($conn, "select * from board where idx='$bno'");
		$board = $sql->fetch_array();
		$sql2 = mysqli_query($conn, "select file_name from file where post_id='$bno'");
		?>

		<!--- main post start-->

		<div class="post_view card">
			<h2>
				<?php echo xss_checker($board['title']); ?>
			</h2>
			<div id="bo_line"></div>
			<div id="bo_content">
				<?php echo xss_checker(nl2br("$board[content]")); ?>
			</div>
			<?php
			while ($file = mysqli_fetch_array($sql2)) {
				?>
				<a href="../upload/<?php echo ($file['file_name']); ?>" download>
					<?php echo ($file['file_name']); ?>
				</a>
				<br>
				<?php
			}
			?>
			<div id="user_info">
				<?php echo xss_checker($board['name']); ?>
				<?php echo xss_checker($board['date']); ?>
				Hit:
				<?php echo xss_checker($board['hit']); ?>
			</div>
			<div id="bo_ser" class="wrapper-button">
				<a class="button-edit" href="edit.php?bno=<?php echo $board['idx']; ?>">edit</a>
				<a class="button-delete" href="delete.php?bno=<?php echo $board['idx']; ?>">delete</a>
			</div>
		</div>

		<!--- reply list start-->
		<?php
		$sql3 = mysqli_query($conn, "select * from reply where post_id='$bno' order by idx desc");
		while ($reply = $sql3->fetch_array()) {
			$user_can_edit_reply = ($reply['name'] === $uid);
			?>
			<div>
				<div class="reply_view card" data-reply-id="<?php echo $reply['idx']; ?>">
					<div>
						<?php if ($user_can_edit_reply) { ?>
							<!-- Edit mode for the reply -->
							<form class="edit-reply-form">
								<textarea
									class="edit-reply-content"><?php echo xss_checker(nl2br($reply['content'])); ?></textarea>
							</form>
						<?php } else { ?>
							<!-- Display the reply content -->
							<h4>
								<?php echo xss_checker(nl2br($reply['content'])); ?>
							</h4>
						<?php } ?>
					</div>
					<div id="user_info">
						<?php echo xss_checker($reply['name']); ?>
						<?php echo $reply['date']; ?>
					</div>
					<div class="wrapper-button">
						<?php if ($user_can_edit_reply) { ?>
							<div class="wrapper-button">
								<button class="button-save-edit">Save</button>
								<button class="button-cancel-edit">Cancel</button>
							</div>
							<button class="button-edit-reply">Edit</button>
							<a class="button-delete" href="reply/reply_delete.php?rno=<?php echo $reply['idx']; ?>">Delete</a>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php
		}
		?>

		<!--- reply input form start-->

		<div class="card">
			<form action="reply/reply_ok.php?idx=<?php echo $bno; ?>" method="post">
				<div style="margin-top:-10px; ">
					<textarea name="content" class="reply_content" id="re_content" placeholder="Content"></textarea>
				</div>
				<div class="wrapper-button">
					<button id="rep_bt" class="re_bt">Reply</button>
				</div>
			</form>
		</div>
	</div>
</body>

</html>