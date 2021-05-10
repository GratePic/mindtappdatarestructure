Endpoint:
https://us-central1-mindtapprelationaldb.cloudfunctions.net/getFeed?uid=1&offset=0

<?php

use Psr\Http\Message\ServerRequestInterface;
                                                             
function getFeed(ServerRequestInterface $request)
{

$passedUID = $_GET["uid"];
$passedOffset = $_GET["offset"];

$username = 'root';
$password = 'MindTapp2021';
$dbName = 'mindtapp';
$connectionName = 'mindtapprelationaldb:us-west2:mindtapp';
$socketDir = getenv('DB_SOCKET_DIR') ?: '/cloudsql';

// Connect using UNIX sockets
$dsn = sprintf(
    'mysql:dbname=%s;unix_socket=%s/%s',
    $dbName,
    $socketDir,
    $connectionName
);

// Connect to the database.
$conn = new PDO($dsn, $username, $password, $conn_config);

if ($conn && isset($passedUID) && isset($passedOffset)){
    
    $feed = array();

    $sql = "SELECT * FROM follow WHERE follower=".$passedUID;
    $following = "SELECT * FROM feed";
    $notFollowing = "SELECT * FROM feed";
    $append = " ORDER BY createdOn DESC LIMIT 6 OFFSET ".$passedOffset; 
    
    $i = 0;
    foreach ($conn->query($sql) as $row) {
        
        if($i==0){

            $followingWhere = " WHERE uid=" . $row['following'];
            $following .= $followingWhere;

            $notFollowingWhere = " WHERE uid<>" . $row['following'];
            $notFollowing .= $notFollowingWhere;
            
        }else{
            
            $followingWhere = " OR uid=" . $row['following'];
            $following .= $followingWhere;

            $notFollowingWhere = " OR uid<>" . $row['following'];
            $notFollowing .= $notFollowingWhere;
        }

        $i++;
    }

    $following = $following . $append;
    foreach ($conn->query($following) as $row) {
        $push = array($row['id'], $row['uid'], $row['bid'], $row['bname'], $row['board'], $row['createdOn']);
        $feed[] = $push;
    }

    $notFollowing = $notFollowing . $append;
    foreach ($conn->query($notFollowing) as $row) {
        $push = array($row['id'], $row['uid'], $row['bid'], $row['bname'], $row['board'], $row['createdOn']);
        $feed[] = $push;
    }

    shuffle($feed);
    return json_encode($feed);

  
}else{
    return 'nope';
}

}