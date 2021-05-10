Endpoint:
https://us-central1-mindtapprelationaldb.cloudfunctions.net/unfollow?follower=1&followed=50

<?php

use Psr\Http\Message\ServerRequestInterface;

function unfollow(ServerRequestInterface $request)
{
 
  $passedFollowerUID = $_GET["follower"];
  $passedFollowedUID = $_GET["followed"];

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

  if ($conn && isset($passedFollowerUID) && isset($passedFollowedUID)){

    $sql = "DELETE FROM follow WHERE follower=" . $passedFollowerUID . " AND following=" . $passedFollowedUID; 

    $result = $conn->query($sql);

    if ($result) {
      return "true";
    }else{
      return "false";
    }

  }else{
    return 'nope';
  }

}

