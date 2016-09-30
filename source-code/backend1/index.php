<?php
    session_start();

    $conn = mysql_connect("mariadb", "guest", "123456789Aa@");
    if ($conn) {
        $dbStatus = "Database connected successfully";
        mysql_close($conn);
    } else {
        $dbStatus = "Could not connect: " . mysql_error();
        throw new Exception($dbStatus);
    }
    /* Generate an unique token for server */
    $_SESSION['_token'] = isset($_SESSION['_token']) ? $_SESSION['_token'] : uniqid();
?>


<!DOCTYPE html>
<html>
<head>
    <title>[Backend 1] NGINX Load Balancing - HTTP and TCP Load Balancer</h1></title>
    <link rel="icon" href="data:;base64,iVBORw0KGgo=">
</head>
<body>
    <h1>[Backend 1] NGINX Load Balancing - HTTP and TCP Load Balancer</h1>
    <p><?= $dbStatus ?></p>
    <p>Current token: <?= $_SESSION['_token'] ?></p>
    <a href="submit.php">Submit your name</a>
</body>
</html>