		<!--- reply list start(legacy)-->
		<?php
		$sql3 = mysqli_query($conn, "select * from reply where post_id='$bno' order by idx desc");
		while($reply = $sql3->fetch_array()){ 
		?>
		<div>
			<div class="reply_view card">
				<div>
					<h4><?php echo xss_checker(nl2br("$reply[content]"));?></h4>
				</div>
				<div id="user_info">
					<?php echo xss_checker($reply['name']);?> <?php echo $reply['date']; ?>
				</div>
				<div class="wrapper-button">
					<a class="button-edit" href="reply/reply_modify.php?rno=<?php echo $reply['idx']; ?>">edit</a>
					<a class="button-delete" href="reply/reply_delete.php?rno=<?php echo $reply['idx']; ?>">delete</a>
				</div>
			</div>
		</div>
		<?php 
		} 
		?>