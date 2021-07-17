<?php

$servername = "localhost";
$username = "*********";
$password = "*********";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
	die("MySQL Connection failed: " . $conn->connect_error);
}

if ($handle = opendir('../data')) {

	$count = 0;

    while (false !== ($entry = readdir($handle))) {

	    $arrayOfUsernames = [];

	    if ($entry == "." || $entry == "..") {  continue; }

		$data = json_decode( file_get_contents('../data/'.$entry) );

		// add author
		// $arrayOfUsernames[] = $data[0]->data->children[0]->data->author;

	    $comments = $data[1]->data->children;
	    foreach ($comments as $comment) {
	    	echo "\nprocessing " . $entry;
	    	recurseComments($comment, $arrayOfUsernames, $entry, $conn);
	    }

    }
    closedir($handle);
}

function recurseComments($children, &$arrayOfUsernames, $filename, &$conn) {
	if ($children->kind != "t1") {
		return;
	}

	if (empty($children->data->author)) {
		return;
	}

	//$arrayOfUsernames[]=$children->data->author;
	//print_r($children->data); die();
	$sql =  "insert into mde.post_replies 
    				(post_id, username, comment, comment_id, link_id) 
    			values 
    				( 
    			        (select id from mde.posts where filename = '".$filename."'), 
    			        '".$children->data->author."', 
    			        '".addslashes($children->data->body)."', 
    			        '".$children->data->id."', 
    			        '".$children->data->link_id."'
    			    )";
	$conn->query($sql);
	echo $conn->error;
	if (!empty($children->data->replies->data->children)) {
		foreach($children->data->replies->data->children as $child) {
			recurseComments($child, $arrayOfUsernames, $filename, $conn);
		}
	}

	return;
}

?>
