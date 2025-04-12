<?php
require_once("config.php");
$insertMsg = "";

if (
    isset($_POST["insert"]) &&
    !empty($_POST["insert_username"]) &&
    !empty($_POST["insert_password"])
) {
    $currentUsername = htmlspecialchars($_POST["insert_username"]);
    $password = htmlspecialchars($_POST["insert_password"]);

    $db = get_pdo_connection();

    $query = $db->prepare("SELECT password FROM User WHERE username = :username");
    $query->bindParam(':username', $currentUsername, PDO::PARAM_STR);
    $query->execute();

    $realPassword = $query->fetch(PDO::FETCH_ASSOC);

    if ($realPassword && $password === $realPassword['password']) {
        $deleteQuery = $db->prepare("DELETE FROM User WHERE username = :currentUsername");
        $deleteQuery->bindParam(':currentUsername', $currentUsername, PDO::PARAM_STR);
        if ($deleteQuery->execute()) {
            $insertMsg = "User deleted successfully.";
        } else {
            $insertMsg = "Error deleting user: " . print_r($deleteQuery->errorInfo(), true);
        }
    } else {
        // If the password doesn't match
        $insertMsg = "Current password is incorrect.";
    }
}

require_once("main.php");
?>
<h2>Delete Your Account :(</h2>

<?php
if (!empty($insertMsg)) {
    echo "$insertMsg<br>";
    $insertMsg = "";
}
?>

<form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
    <label>current username: <input type="text" name="insert_username"></label><br>
    <label>password: <input type="password" name="insert_password"></label><br>
    <input type="submit" name="insert" value="Delete Account">
</form>
</body>
</html>
