<?php

$connect = new MongoDB\Driver\Manager("mongodb://localhost:27017");
//------------------------------------USER INFO FEATURES-----------------------------------------------
function login($email, $password){
	global $connect;
	$filter = ["email"=>$email, "password"=>$password];
	$query = new MongoDB\Driver\Query($filter);
	$cursor = $connect->executeQuery('A2.users', $query);
	$result = iterator_to_array($cursor);
	if(!empty($result)){
		return true;
	}
	return false;
}

function getUserInfo($email){
	global $connect;
	$filter = ["email"=>$email];
	$query = new MongoDB\Driver\Query($filter);
	$cursor = $connect->executeQuery('A2.users', $query);
	$result = iterator_to_array($cursor);
	return $result[0];
}

function userExistCheck($email){
	global $connect;
	$filter = ["email"=>$email];
	$query = new MongoDB\Driver\Query($filter);
	$cursor = $connect->executeQuery('A2.users', $query);
	$result = iterator_to_array($cursor);
	if(!empty($result)){
		return true;
	}
	return false;
}

function insertUser($email, $password, $fname, $sname, $gender, $address, $dob){
	global $connect;
	$bulk = new MongoDB\Driver\BulkWrite;
	$newUser = ['_id' => new MongoDB\BSON\ObjectId, 
				'email' => $email,
				'password' => md5($password),
				'f_name' => $fname,
				's_name' => $sname,
				'gender' => $gender,
				'status' => 'default',
				'location' => $address,
				'dob' =>  new MongoDB\BSON\UTCDateTime(new DateTime($_POST['dob'])),
				'v_lv' => 0
				];
	$bulk->insert($newUser);
	$connect->executeBulkWrite('A2.users', $bulk);
}

//----------------------------------------------USER FRIENDSHIP FEATURES-----------------------------------------------------------

function updateDetail($email, $sname, $status, $location, $v_lv){
	global $connect;
	$bulk = new MongoDB\Driver\BulkWrite;
	$bulk->update(
	    [ 'email' => $email ],
	    [ '$set' => 
	    	[ 's_name' => $sname,
	    	  'status' => $status,
	    	  'location' => $location,
	    	  'v_lv' => $v_lv
	     	]
	    ]
	);
	$connect->executeBulkWrite('A2.users', $bulk);
}
function insertApply($senderEmail, $receiverEmail){
	global $connect;
	$bulk = new MongoDB\Driver\BulkWrite;
	$newApply = ['_id' => new MongoDB\BSON\ObjectId, 
				'usera' => $senderEmail,
				'userb' => $receiverEmail,
				'status' => false
				];
	$bulk->insert($newApply);
	$connect->executeBulkWrite('A2.apply', $bulk);
}

function applyCheck($senderEmail, $receiverEmail){

	global $connect;
	$filter = [
        '$or'  => [
	        	[
		        	'$and' => [ 
		        		['usera' => $senderEmail], 
		        		['userb' => $receiverEmail] 
		        	]
	        	],

        		[
        			'$and' => [ 
        				['usera' => $receiverEmail], 
        				['userb' => $senderEmail] 
        			] 
        		]

        ]
    ];
   $query = new MongoDB\Driver\Query($filter);
   $cursor = $connect->executeQuery("A2.apply", $query);
   $result = iterator_to_array($cursor);
   return $result;
}

function applyToMe($email){
	global $connect;
	$filter = ['userb' => $email];
	$query = new MongoDB\Driver\Query($filter);
	$cursor = $connect->executeQuery("A2.apply", $query);
	$result = iterator_to_array($cursor);
	return $result;
}

function applyFromMe($email){
	global $connect;
	$filter = ['usera' => $email];
	$query = new MongoDB\Driver\Query($filter);
	$cursor = $connect->executeQuery("A2.apply", $query);
	$result = iterator_to_array($cursor);
	return $result;
}

function getFriends($email){
	global $connect;
	$filter = ['$or' => [ ['usera' => $email], ['userb' => $email] ]];
	$query = new MongoDB\Driver\Query($filter);
	$cursor = $connect->executeQuery("A2.friendship", $query);
	$result = iterator_to_array($cursor);
	return $result;
}

function insertFriend($sender, $receiver){
	global $connect;
	$bulk = new MongoDB\Driver\BulkWrite;
	$newApply = ['_id' => new MongoDB\BSON\ObjectId, 
				'usera' => $sender,
				'userb' => $receiver,
				'start_date' => new MongoDB\BSON\UTCDateTime(new DateTime(date('Y-m-d H:i:s')))
				];
	$bulk->insert($newApply);
	$connect->executeBulkWrite('A2.friendship', $bulk);
}

function updateApplyStatus($sender, $receiver){
	global $connect;
	$bulk = new MongoDB\Driver\BulkWrite;

	$bulk->update(
	    [ '$and' => [ ['usera' => $sender], ['userb' => $receiver] ] ],
	    [ '$set' => ['status' => true] ]
	);
	$connect->executeBulkWrite('A2.apply', $bulk);
}

function deleteApply($sender, $receiver){
	global $connect;
	$bulk = new MongoDB\Driver\BulkWrite;
	$bulk->delete(
	    	[ '$and' => [ ['usera' => $sender], ['userb' => $receiver] ] ]
	    );
	$connect->executeBulkWrite('A2.apply', $bulk);
}

//--------------------------------------------USER POST FEATURES---------------------------------------------------------------------


function insertPost($email, $content){
	global $connect;
	$bulk = new MongoDB\Driver\BulkWrite;
	$newApply = ['_id' => new MongoDB\BSON\ObjectId, 
				'email' => $email,
				'content' => $content,
				'reply' => array(),
				'like' => 0,
				'start_date' => new MongoDB\BSON\UTCDateTime(new DateTime(date('Y-m-d H:i:s'))),
				'timestamp' => time()
				];
	$bulk->insert($newApply);
	$connect->executeBulkWrite('A2.post', $bulk);
}

function getPost(){
	global $connect;
	$query = new MongoDB\Driver\Query([],['sort' => ['start_date' => -1]]);
	$cursor = $connect->executeQuery('A2.post', $query);
	$result = iterator_to_array($cursor);
	return $result;
}

function getPostUser($id){
	global $connect;
	$filter = ['_id' => new MongoDB\BSON\ObjectID($id)];
	$query = new MongoDB\Driver\Query($filter);
	$cursor = $connect->executeQuery('A2.post', $query);
	$result = iterator_to_array($cursor);
	return $result[0];
}

function insertReply($poster, $replyer, $postID, $content){
	global $connect;
	$bulk = new MongoDB\Driver\BulkWrite;
	$bulk->update(
	    [ '_id' => new MongoDB\BSON\ObjectID($postID) ],
	    [
	    	'$push' => [ 'reply' =>
	    		array(
	    			'_id' => new MongoDB\BSON\ObjectId,
	    			'parent' => null,
	    			'poster' => $poster,
	    			'replyer' => $replyer, 
	    			'content' => $content,
	    			'like' => 0,
	    			'start_date' => new MongoDB\BSON\UTCDateTime(new DateTime(date('Y-m-d H:i:s'))),
	    			'timestamp' => time()
	    			)]
	    ]
	);
	$connect->executeBulkWrite('A2.post', $bulk);

}

function getReplyerToPoster($replyID){
	global $connect;
	//elemMatch does not work, but I dont want to change it to avoid more unexpected exceptions
	$filter = ['reply' =>  ['$elemMatch' => ['_id' => new MongoDB\BSON\ObjectID($replyID)] ] ];
	$query = new MongoDB\Driver\Query($filter);
	$cursor = $connect->executeQuery('A2.post', $query);
	$result = iterator_to_array($cursor);

	for ($i=0; $i < sizeof($result[0]->reply); $i++) { 
		if ($result[0]->reply[$i]->_id == new MongoDB\BSON\ObjectID($replyID)) {
			return $result[0]->reply[$i]->replyer;
		}
	}
	return array("Error!");
}

function insertReplyII($poster, $replyer, $postID, $content, $parent){
	global $connect;
	$bulk = new MongoDB\Driver\BulkWrite;
	$bulk->update(
	    [ '_id' => new MongoDB\BSON\ObjectID($postID) ],
	    [
	    	'$push' => [ 'reply' =>
	    		array(
	    			'_id' => new MongoDB\BSON\ObjectId,
	    			'parent' => new MongoDB\BSON\ObjectID($parent),
	    			'poster' => $poster,
	    			'replyer' => $replyer, 
	    			'content' => $content,
	    			'like' => 0,
	    			'start_date' => new MongoDB\BSON\UTCDateTime(new DateTime(date('Y-m-d H:i:s'))),
	    			'timestamp' => time()
	    			)]
	    ]
	);
	$connect->executeBulkWrite('A2.post', $bulk);

}

//----------------------------------------------USER LIKE FEATURES------------------------------------------------------------

function insertLikePost($post, $user){
	global $connect;
	$bulk = new MongoDB\Driver\BulkWrite;
	$newLikePost = ['post' => (int)$post, 
					'user' => $user
				];
	$bulk->insert($newLikePost);
	postLikeUpdate($post);
	$connect->executeBulkWrite('A2.like', $bulk);
}

function getPostByTime($time){
	global $connect;
	$filter = ['timestamp' => (int)$time];
	$query = new MongoDB\Driver\Query($filter);
	$cursor = $connect->executeQuery('A2.post', $query);
	$result = iterator_to_array($cursor);
	return $result[0]->like;
}

function postLikeUpdate($post){
	global $connect;
	$bulk = new MongoDB\Driver\BulkWrite;

	$like = (int)getPostByTime($post) + 1;

	echo $like;
	// var_dump(getPostByTime($post));
	$bulk->update(
	    ['timestamp' => (int)$post],
	    [ '$set' => [ 'like' => $like] ]
	);
	$connect->executeBulkWrite('A2.post', $bulk);
}

function getLike($id){
	global $connect;
	$filter = ['_id' => new MongoDB\BSON\ObjectID($id)];
	$query = new MongoDB\Driver\Query($filter);
	$cursor = $connect->executeQuery('A2.post', $query);
	$result = iterator_to_array($cursor);
	return $result[0]->like;
}

function votedPost($user, $post){
	global $connect;
	$filter = ["user"=>$user, "post"=>(int)$post];
	$query = new MongoDB\Driver\Query($filter);
	$cursor = $connect->executeQuery('A2.like', $query);
	$result = iterator_to_array($cursor);
	if(!empty($result)){
		return true;
	}
	return false;

}

function getLikeReply($postid, $replyid){
	global $connect;
	$filter = ['_id' => new MongoDB\BSON\ObjectID($postid)];
	$query = new MongoDB\Driver\Query($filter);
	$cursor = $connect->executeQuery('A2.post', $query);
	$result1 = iterator_to_array($cursor);
	$replys = $result1[0]->reply;

	for ($i=0; $i < sizeof($replys); $i++) {
		if ($replyid == $replys[$i]->_id) {
			return $replys[$i]->like;
		}
	}
	return "error!";
}

function insertLikeReply($time, $user){
	global $connect;
	$bulk = new MongoDB\Driver\BulkWrite;
	$newLikePost = ['post' => (int)$time, 
					'user' => $user
				];
	$bulk->insert($newLikePost);
	$connect->executeBulkWrite('A2.like', $bulk);
}


function getLikeReplyNum($replyid){
	global $connect;
	$filter = ["post"=>(int)$replyid];
	$query = new MongoDB\Driver\Query($filter);
	$cursor = $connect->executeQuery('A2.like', $query);
	$result = iterator_to_array($cursor);
	$likeNum = sizeof($result);
	return $likeNum;
}

function getLikedUser($timestamp){
	global $connect;
	$filter = ['post'=>(int)$timestamp];
	$query = new MongoDB\Driver\Query($filter);
	$cursor = $connect->executeQuery('A2.like', $query);
	$result = iterator_to_array($cursor);
	$users = "";
	for ($i=0; $i < sizeof($result); $i++) { 
		$users .= "[".getUserInfo($result[$i]->user)->s_name."] ";
	}
	return $users;

}

//---------------------------------------- VIEW LEVEL FEATURES--------------------------------------------------

function checkFriendship($user, $poster){
	global $connect;
	$filter = [
        '$or'  => [
	        	[
		        	'$and' => [ 
		        		['usera' => $user], 
		        		['userb' => $poster] 
		        	]
	        	],

        		[
        			'$and' => [ 
        				['usera' => $poster], 
        				['userb' => $user] 
        			] 
        		]

        ]
    ];
   $query = new MongoDB\Driver\Query($filter);
   $cursor = $connect->executeQuery("A2.friendship", $query);
   $result = iterator_to_array($cursor);
   return $result;
   	if(!empty($result)){
		return true;
	}
	return false;
}



	// global $connect;
	// $bulk = new MongoDB\Driver\BulkWrite;
	// $bulk->delete(
	//     	[ '$and' => [ ['usera' => $sender], ['userb' => $receiver] ] ]
	//     );
	// $connect->executeBulkWrite('A2.apply', $bulk);

//------------------------------------------------DELETE USER FEATURES--------------------------------------------------------

function deleteLikes($email){
	global $connect;
	$bulk = new MongoDB\Driver\BulkWrite;
	$bulk->delete(
	    	['user' => $email]
	    );
	$connect->executeBulkWrite('A2.like', $bulk);
}

function deleteUserApply($email){
	global $connect;
	$bulk = new MongoDB\Driver\BulkWrite;
	$bulk->delete(
	    	[
	    		'$or' =>[ ['usera' => $email], ['userb' => $email] ]
	    	]
	    );
	$connect->executeBulkWrite('A2.apply', $bulk);
}

function deleteFriendship($email){
	global $connect;
	$bulk = new MongoDB\Driver\BulkWrite;
	$bulk->delete(
	    	[
	    		'$or' =>[ ['usera' => $email], ['userb' => $email] ]
	    	]
	    );
	$connect->executeBulkWrite('A2.friendship', $bulk);
}

function deletePosts($email){
	global $connect;
	$bulk = new MongoDB\Driver\BulkWrite;
	$bulk->delete(
	    	['email' => $email]
	    );
	$connect->executeBulkWrite('A2.post', $bulk);
}

function deleteReplys($email){

	global $connect;
	$query = new MongoDB\Driver\Query([]);
	$cursor = $connect->executeQuery('A2.post', $query);
	$result = iterator_to_array($cursor);

	//go through all posts
	for ($i=0; $i < sizeof($result); $i++) {
		//go through all replys of one post
		for ($j=0; $j < sizeof($result[$i]->reply); $j++) { 

			//check replys releated to the deleting user
			//if user is poster or replyer of this reply...

			if ($result[$i]->reply[$j]->poster == $email || $result[$i]->reply[$j]->replyer) {
				
				$bulk = new MongoDB\Driver\BulkWrite;
				//delete poster user
				$bulk->update(
				    [ '_id' => new MongoDB\BSON\ObjectID($result[$i]->_id) ],
				    ['$pull' => ['reply' => array('poster' => $email)] ] 
				);

				//delete replyer user
				$bulk->update(
				    [ '_id' => new MongoDB\BSON\ObjectID($result[$i]->_id) ],
				    ['$pull' => ['reply' => array('replyer' => $email)] ] 
				);
				$connect->executeBulkWrite('A2.post', $bulk);

			}
		}
	}


}

function deleteUser($email){
	global $connect;
	$bulk = new MongoDB\Driver\BulkWrite;
	$bulk->delete(
	    	['email' => $email]
	    );
	$connect->executeBulkWrite('A2.users', $bulk);
}

?>