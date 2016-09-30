<?php
    session_start();
    date_default_timezone_set("Asia/Bangkok");

    /* Generate an unique token for server */
    $_SESSION['_token'] = isset($_SESSION['_token']) ? $_SESSION['_token'] : uniqid();


    $conn = new mysqli("mariadb", "guest", "123456789Aa@", "mydb") or die("Connection failed: {$conn->connect_error}");


    /* Insert form data into MySQL DB */
    function validate(&$message) {
        if (!isset($_POST['name']) || empty($_POST['name'])) {
            $message = "The name is required.";
        }
        if (!isset($_POST['_token']) || $_POST['_token'] !== $_SESSION['_token']) {
            $message = "Csrf token mismatch.";
        }
        if (empty($message)) {
            return true;
        }
        return false;
    }
    $message = "";
    if (isset($_POST['submit']) && validate($message)) {
        $createdAt = date('Y-m-d H:i:s');
        $stmt = $conn->prepare("INSERT INTO users (name, created_at) VALUES (?, ?)");
        $stmt->bind_param("ss", $_POST['name'], $createdAt);
        if ($stmt->execute() === true) {
            $message = "Your name has been successfully submitted.";
        } else {
            $message = $conn->error;
        }
    }

    /* Fetch all users in DB */
    $stmt = $conn->prepare("SELECT id, name, created_at FROM users ORDER BY id DESC");
    $stmt->execute();
    $resultSet = $stmt->get_result();
    $users = $resultSet->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>[Backend 1] Submit your name</title>
    <link rel="icon" href="data:;base64,iVBORw0KGgo=">
</head>
<body>
    <h1>[Backend 1] Submit your name</h1>
    <?php if (!empty($message)) { ?><p><?= $message ?></p><?php } ?>
    <form action="submit.php" method="POST">
        <input type="hidden" name="_token" value="<?= $_SESSION['_token'] ?>">
        <p>
            <label for="name">Your Name:</label>
            <input type="text" name="name" id="name">
        </p>
        <input type="submit" name="submit">
    </form>
    <p>
        <a href="index.php">Back to Top</a>
    </p>
    <hr>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Created at</th>
        </tr>
        <?php if (empty($users)) { ?>
            <tr>We have no users to show</tr>
        <?php } else { ?>
            <?php foreach ($users as $user) { ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= $user['name'] ?></td>
                <td><?= $user['created_at'] ?></td>
            </tr>
            <?php } ?>
        <?php } ?>
    </table>
</body>
</html>