<?php
require_once("config.php");
$insertMsg = "";

if (
    isset($_POST["insert"]) &&
    !empty($_POST["insert_username"]) &&
    !empty($_POST["insert_password"]) &&
    !empty($_POST["insert_new_password"])
) {
    $currentUsername = htmlspecialchars($_POST["insert_username"]);
    $currentPassword = htmlspecialchars($_POST["insert_password"]);
    $newPassword = htmlspecialchars($_POST["insert_new_password"]);

    $db = get_pdo_connection();

    $query = $db->prepare("SELECT password FROM User WHERE username = :username");
    $query->bindParam(':username', $currentUsername, PDO::PARAM_STR);
    $query->execute();

    $realPassword = $query->fetch(PDO::FETCH_ASSOC);

    if ($realPassword && $currentPassword === $realPassword['password']) {
        $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $updateQuery = $db->prepare("UPDATE User SET password = :newPassword WHERE username = :username");
        $updateQuery->bindParam(':newPassword', $hashedNewPassword, PDO::PARAM_STR);
        $updateQuery->bindParam(':username', $currentUsername, PDO::PARAM_STR);

        if ($updateQuery->execute()) {
            $insertMsg = "Password updated successfully.";
        } else {
            $insertMsg = "Error updating password: " . print_r($updateQuery->errorInfo(), true);
        }
    } else {
        $insertMsg = "Current password is incorrect.";
    }
}

require_once("main.php");
?>

<h2>Change your Password</h2>

<?php
if (!empty($insertMsg)) {
    echo "$insertMsg<br>";
    $inserMsg = "";
}
?>

<form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
    <label>Current username: <input type="text" name="insert_username" ></label><br>
    <label>Current password: <input type="password" name="insert_password" ></label><br>
    <label>New password: <input type="password" name="insert_new_password" ></label><br>
    <input type="submit" name="insert" value="Change Password">
</form>


