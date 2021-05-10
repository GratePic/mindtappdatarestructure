Endpoint:
//https://us-central1-mindtapprelationaldb.cloudfunctions.net/updateFeed?uid=1&bid=%22cloudTest%22&bname=%22cloudTest%22&board=1

<?php

use Psr\Http\Message\ServerRequestInterface;

function updateFeed(ServerRequestInterface $request)
{
    //Passed Variables
    $passedUID   = $_GET["uid"];
    $passedBID   = $_GET["bid"];
    $passedBNAME = $_GET["bname"];
    $passedBOARD = $_GET["board"];

    //DB Connection
    $username       = 'root';
    $password       = 'MindTapp2021';
    $dbName         = 'mindtapp';
    $connectionName = 'mindtapprelationaldb:us-west2:mindtapp';
    $socketDir      = getenv('DB_SOCKET_DIR') ?: '/cloudsql';

    //Connect using UNIX sockets
    $dsn = sprintf(
        'mysql:dbname=%s;unix_socket=%s/%s',
        $dbName,
        $socketDir,
        $connectionName
    );

    // Connect to the database.
    $conn = new PDO($dsn, $username, $password, $conn_config);

    if ($conn && isset($passedUID) && isset($passedBID) && isset($passedBNAME)  && isset($passedBOARD)){  

      $sql = "INSERT INTO feed (uid, bid, bname, board) VALUES (" . $passedUID . "," . $passedBID . "," . $passedBNAME . "," . $passedBOARD . ")"; 

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
