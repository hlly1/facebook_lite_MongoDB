<?php
	require 'tools.php';
	session_start();
	if (empty($_SESSION['email']) || !isset($_SESSION['email'])){
		header("Location: index.php");
	}
	// get data for user details
	$userInfo = getUserInfo($_SESSION['email']);
	$applyToMe = applyToMe($_SESSION['email']);
	$applyFromMe = applyFromMe($_SESSION['email']);
	$getFriends = getFriends($_SESSION['email']);
	$getPost = getPost();
?>


<html>
<header class="navbar navbar-dark navColor">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<title>Facebook-Lite | Assignment 1</title>
<link href="css/custom.css" rel="stylesheet">

      <div class="container">
        <a class="navTitle" href="home.php">facebook</a>
        <a href="logout.php" class="text-right btn btn-outline-warning">Logout</a>
      </div>
			<!-- Nav Bar -->
<style>
.emoji {
  font-family: Segoe UI Emoji, Segoe UI Symbol, Quivira, Symbola;
}
</style>

</header>

<body>
<div class="jumbotron text-center">
	<div class="row">

		<div class="col-sm-3">
			<div class="card shadow">
				<div class="card-header text-left"><h5>Personal Details: </h5>

<!--................................................................ EDIT user ..........................................................-->


					<form action="userEdit.php" method="POST" id="userEdit">
						<input type="submit" class="btn btn-outline-primary" value="edit">
					</form>

<!--................................................................ DELETE user ..........................................................-->
					<form action="deleteUser.php" method="POST" id = "userDelete" onsubmit = "return confirmDelete()" >
						<input type="submit" class="btn btn-outline-danger" value="Delete my Account" form="userDelete">
					</form>

				</div>
					<div class="card-body">
						<ul class="list-group list-group-flush">
				<?php 
					
					echo "<div class = 'text-left'><label>Email: </label><br><li class='list-group-item'>".$userInfo->email."</li>";
					echo "<label>Full Name: </label><br><li class='list-group-item'>".$userInfo->f_name."</li>";
					echo "<label>Screen Name: </label><br><li class='list-group-item'>".$userInfo->s_name."</li>";
					echo "<label>Gender: </label><br><li class='list-group-item'>".$userInfo->gender."</li>";
					echo "<label>Date of Brith: </label><br><li class='list-group-item'>".$userInfo->dob->toDateTime()->format('Y-m-d')."</li>";
					echo "<label>Status: </label><br><li class='list-group-item'>".$userInfo->status."</li>";
					echo "<label>Location: </label><br><li class='list-group-item'>".$userInfo->location."</li>";
					if(($userInfo->v_lv) == 0){
						echo "<label>Who can see my posts?: </label><br><li class='list-group-item'>Everyone can watch your posts.</li>";
					}elseif (($userInfo->v_lv) == 1) {
						echo "<label>Who can see my posts?: </label><br><li class='list-group-item'>Only your friends can watch your posts.</li>";
					}elseif (($userInfo->v_lv) == 2) {
						echo "<label>Who can see my posts?: </label><br><li class='list-group-item'>No one but you can watch your posts.</li>";
					}
				?>
					</ul> 
					</div>
				</div>

		</div>

		<div class="col-sm-6">
			<h2>Add your post here...</h2><br>

			<form action="addPosts.php" method="POST" id="postForm">
				<textarea class="form-control shadow" rows="5" name="post" form = "postForm" required></textarea>
				<input type="submit" class="btn btn-primary" form = "postForm">
			</form>

			

			<br>
			<h5>All Posts: </h5><br>
<!--................................................................ add post and comments here .........................................-->

			<?php 
				for ($p = 0; $p < sizeof($getPost); $p++) {

						$userVlv = (getUserInfo($getPost[$p]->email)->v_lv);
						// echo $userVlv;
						$condition1 = ($userVlv == 0);

						$condition2 = checkFriendship($_SESSION['email'], $getPost[$p]->email);

						$condition3 = ($getPost[$p]->email == $_SESSION['email'] && $userVlv == 2);

						$condition4 = ($userVlv == 1 && $getPost[$p]->email == $_SESSION['email']);
						$condition5 = ($condition1 || ($condition2 && $userVlv == 1) || $condition3 || $condition4) && userExistCheck($getPost[$p]->email);

			if($condition5){
					echo "
						<div class='card shadow'>
							<div class = 'card-header text-left'><h5>[".getUserInfo($getPost[$p]->email)->s_name."] posted:</h5></div>
							<div class='card-body text-left'>".$getPost[$p]->content."&nbsp&nbsp";

							if(getLikedUser($getPost[$p]->timestamp) != ""){
								echo "<p class = 'text-muted'>".getLikedUser($getPost[$p]->timestamp)." liked this post</p>";
							}

							echo "<input type = 'hidden' name = 'postIDforComment".$getPost[$p]->_id."' value = '".$getPost[$p]->_id."'>
								<button type = 'button' data-toggle='modal' data-target='#commentModal' class = 'btn-primary btnRadius' onClick = 'passID(this.value)' value = '".$getPost[$p]->_id."'>
									reply
								</button>";
								$currentUserVoted = votedPost($_SESSION['email'], $getPost[$p]->timestamp);
								if(!$currentUserVoted){
									$postlike = $getPost[$p]->timestamp;
									$userEMAIL = $_SESSION['email'];
									echo "<button type = 'button' class = 'btn-primary btnRadius float-right' onClick = 'likePost(".$postlike.")'>
												<span class=emoji>&#x1F44D Like  </span>
											</button>";

								}else{
									echo "<button type = 'button' class = 'btn-danger btnRadius float-right' onClick = 'votedLike()'>
												<span class=emoji>&#x1F44D Liked  </span>
											</button>";
								}

					echo "</div>";
					

//-----------------------------------------------------    REPLY  ----------------------------------------------------------------------------------
					$reply = $getPost[$p]->reply;
					for ($re = 0; $re < sizeof($reply); $re++) {
						$replyerLv = getUserInfo($reply[$re]->replyer)->v_lv;
						$commCondition1 = ($replyerLv == 0);
						$commCondition2 = (checkFriendship($_SESSION['email'], $reply[$re]->replyer) && $replyerLv == 1) || ($replyerLv == 1 && $reply[$re]->replyer == $_SESSION['email']);
						$commCondition3 = ($reply[$re]->replyer == $_SESSION['email']) && $replyerLv == 2;
						
						$parentCommentUserID = $reply[$re]->poster;
						
						$commCondition5I = ($reply[$re]->replyer == $_SESSION['email'] && $replyerLv == 2) || ($replyerLv == 2 && $getPost[$p]->email == $_SESSION['email']);

						$commCondition5II = ($replyerLv == 2 && $parentCommentUserID == $_SESSION['email']) || ($reply[$re]->replyer == $_SESSION['email'] && $replyerLv == 2) || ($replyerLv == 2 && $parentCommentUserID == $_SESSION['email']);
						
						$combination1 = ($commCondition1 || $commCondition2 || $commCondition3 || $commCondition5I);
						$combination2 = ($commCondition1 || $commCondition2 || $commCondition3 || $commCondition5II);

						if($reply[$re]->parent == null && $combination1 && userExistCheck($reply[$re]->replyer)){
							echo "<li class='list-group-item text-left'>
												>>--[".getUserInfo($reply[$re]->replyer)->s_name."] reply: ".$reply[$re]->content."&nbsp&nbsp";

												if(getLikedUser($reply[$re]->timestamp) != ""){
													echo "<p class = 'text-muted'>".getLikedUser($reply[$re]->timestamp)." liked this post</p>";
												}

												echo "<button type = 'button' data-toggle='modal' data-target='#commCommModal' class = 'btn-primary btnRadius' onClick = 'passID2(this.value);' value = '".$reply[$re]->_id."+".$getPost[$p]->_id."'>
													reply
												</button>"; 


								$currentUserVoted = votedPost($_SESSION['email'], $reply[$re]->timestamp);
								if(!$currentUserVoted){
									$parentPostID = $getPost[$p]->timestamp;
									$replyID = $reply[$re]->timestamp;

									$userEMAIL = $_SESSION['email'];
									// var_dump($postlike);
									echo "<button type = 'button' class = 'btn-primary btnRadius float-right' onClick = 'likeReply(".$parentPostID.",".$reply[$re]->timestamp.")'>
												<span class=emoji>&#x1F44D Like </span>
											</button>";


								}else{
									echo "<button type = 'button' class = 'btn-danger btnRadius float-right' onClick = 'votedLike()'>
												<span class=emoji>&#x1F44D Liked </span>
											</button>";
								}


						}elseif($combination2){
							echo "<li class='list-group-item text-left'>
												>>>>----[".getUserInfo($reply[$re]->replyer)->s_name."] reply [".getUserInfo(getReplyerToPoster($reply[$re]->parent))->s_name."]: ".$reply[$re]->content."&nbsp&nbsp";

												if(getLikedUser($reply[$re]->timestamp) != ""){
													echo "<p class = 'text-muted'>".getLikedUser($reply[$re]->timestamp)." liked this post</p>";
												}

												echo "<button type = 'button' data-toggle='modal' data-target='#commCommModal' class = 'btn-primary btnRadius' onClick = 'passID2(this.value);' value = '".$reply[$re]->_id."+".$getPost[$p]->_id."'>
													reply
												</button>
												";

								$currentUserVoted = votedPost($_SESSION['email'], $reply[$re]->timestamp);
								if(!$currentUserVoted){
									$parentPostID = $getPost[$p]->timestamp;
									$replyID = $reply[$re]->timestamp;

									$userEMAIL = $_SESSION['email'];
									echo "<button type = 'button' class = 'btn-primary btnRadius float-right' onClick = 'likeReply(".$parentPostID.",".$reply[$re]->timestamp.")'>
												<span class=emoji>&#x1F44D Like </span>
											</button>";

								}else{
									echo "<button type = 'button' class = 'btn-danger btnRadius float-right' onClick = 'votedLike()'>
												<span class=emoji>&#x1F44D Liked </span>
											</button>";
								}
								echo "</li>";
						}

					}

					echo "</div><br>";
					
		}
		}



			?>




		</div>


		<div class="col-sm-3">
			
			<div class="card shadow">
				<div class="card-header">
			    	<h5>Friendship</h5>
			  	</div>
			  	<div class="card-body">
			  	<?php
			  		for ($f = 0; $f < sizeof($getFriends); $f++) {
			  			if($getFriends[$f]->usera == $_SESSION['email']){
			  				echo "[".getUserInfo($getFriends[$f]->userb)->s_name."] ---(started at)--- [".$getFriends[$f]->start_date->toDateTime()->format('Y-m-d')."]<br>";
			  			}else{
			  				echo "[".getUserInfo($getFriends[$f]->usera)->s_name."] ---(started at)--- [".$getFriends[$f]->start_date->toDateTime()->format('Y-m-d')."]<br>";
			  			}
			  		}
			  	?>
				</div>
			</div><br>

			<div class="card shadow">

				<div class="card-header">
			    	<h5>Add Friends by Email</h5>
			  	</div>

				<div class="card-body">
				  	<form action="friendApply.php" method="POST" id = "applyForm">
						<input type = "hidden" value = "<?php echo $_SESSION['email'] ?>" name = "useraEmail" form = "applyForm">
						<input type="email" name = "userbEmail" form = "applyForm" required>
						<input type="submit" value="Send Request" form = "applyForm" class="btn btn-primary" >
					</form>
				</div>



				<div class="card-header">
			    	<h5>Requests(to me):</h5>
			  	</div>

			  	<div class="card-body text-left">
			  		<?php
			  			for($i = 0; $i < sizeof($applyToMe); $i++){
			  				if(!($applyToMe[$i]->status)){
				  				$sender = $applyToMe[$i]->usera;
				  				echo "<li class='list-group-item'>";
				  				echo "[".getUserInfo($sender)->s_name."] wants to add you as a friend.";
				  				echo "
				  					<div class = 'row'>
				  						<div class = 'col-sm-6'>
						  					<form action = 'accept.php' method = 'POST' id = 'accept".$applyToMe[$i]->_id."'>
							  					<input type = 'hidden' value = '".$sender."' name = 'senderAccept' form = 'accept".$applyToMe[$i]->_id."'>
							  					<br><input type = 'submit' class = 'btn btn-primary' value = 'accpet' form = 'accept".$applyToMe[$i]->_id."'>
						  					</form>
						  				</div>
						  				<div class = 'col-sm-6'>
						  					<form action = 'reject.php' method = 'POST' id = 'reject".$applyToMe[$i]->_id."'>
							  					<input type = 'hidden' value = '".$sender."' name = 'senderReject' form = 'reject".$applyToMe[$i]->_id."'>
							  					<br><input type = 'submit' class = 'btn btn-danger' value = 'reject' form = 'reject".$applyToMe[$i]->_id."'>
						  					</form>
						  				</div>
				  					</div>
				  				";
				  				echo "</li>";
			  				}else{
								echo "<li class='list-group-item'>[ ".getUserInfo($applyToMe[$i]->usera)->s_name." ] 
								is already your friend. <input class='btn btn-lg btn-success' value = 'accepted' disabled></li>";
							}
			  			}

			  			

			  		?>
			  	</div>

			  	<div class="card-header">
			    	<h5>Requests(from me):</h5>
			  	</div>

				<div class="card-body text-left">
					<?php

			  			for($a = 0; $a < sizeof($applyFromMe); $a++){
			  				if(!($applyFromMe[$a]->status)){
			  					echo "<li class='list-group-item'>Waiting [ ".getUserInfo($applyFromMe[$a]->userb)->s_name." ] for accepting your request.</li>";
			  				}
			  			}
			  		?>
				</div>



			</div>




		</div>
</div>

<div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New Comment:</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <form action="addComment.php" method = "POST" id = "addComment">
          <div class="form-group">
            <label for="message-text" class="col-form-label">Comment:</label>
            <textarea class="form-control" id="message-text" form="addComment" name="newComment" required></textarea>
            <input type="hidden" value="" id="postID" name="postID" form="addComment">
          </div>
        </form>


      </div>
      <div class="modal-footer">
        <input type="submit" form="addComment" class="btn btn-primary" value="Add a New Comment">

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="commCommModal" tabindex="-1" role="dialog" aria-labelledby="commCommModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New Comment:</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <form action="addCommentComment.php" method = "POST" id = "addCommentComment">
          <div class="form-group">
            <label for="message-text" class="col-form-label">Comment:</label>
            <textarea class="form-control" id="message-text" form="addCommentComment" name="newCommentComment" required></textarea>
            <input type="hidden" value="" id="postID2" name="postID2" form="addCommentComment">
            <input type="hidden" value="" id="parent" name="parent" form="addCommentComment">
          </div>
        </form>

      </div>
      <div class="modal-footer">
        <input type="submit" form="addCommentComment" class="btn btn-primary" value="Add a New Comment">

      </div>
    </div>
  </div>
</div>

<form action="addLikePost.php" method="POST" id = "likePost">
	<input type="hidden" name = "postLikeUserID" id = "postLikeUserID" value="" form="likePost">
	<input type="hidden" name = "postIDlike" id = "postIDlike" value="" form = "likePost">
</form>
	
<form action="addLikeComment.php" method="POST" id = "likeComment">
	<input type="hidden" name = "commLikeUserID" id = "commLikeUserID" value="" form="likeComment">
	<input type="hidden" name = "commIDlike" id = "commIDlike" value="" form="likeComment">
	<input type="hidden" name = "parentID" id = "parentID" value="" form="likeComment">
</form>

</body>
<script type="text/javascript">

	function test(){
		alert('js working!');
	}

	function passID(ID){
		console.log(ID);
		document.getElementById("postID").value = ID;
	}

	function passID2(ID){
		console.log(ID);
		document.getElementById("postID2").value = ID;
	}

	function likePost(post){
		var user = '<?php echo $_SESSION["email"];?>';
		document.getElementById("postLikeUserID").value = user;
		document.getElementById("postIDlike").value = post;
		document.getElementById("likePost").submit();
	}

	function likeReply(parentPost, reply){
		var user = '<?php echo $_SESSION["email"];?>';
		document.getElementById("commLikeUserID").value = user;
		document.getElementById("parentID").value = parentPost;
		document.getElementById("commIDlike").value = reply;
		document.getElementById("likeComment").submit();
	}

	function votedLike(){
		alert("You can only vote it once!");
	}

	function confirmDelete(){
		var confirmDelete = confirm("WARNING: ARE YOU SURE TO DELETE YOUR ACCOUNT?");
		if (confirmDelete == true){

			return true;

		}else{

			return false;
		}
	}

</script>
</html>