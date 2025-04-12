<?php
require_once("config.php");
$insertMsg = "";

if (
    isset($_POST["insert"]) &&
    !empty($_POST["insert_username"]) &&
    !empty($_POST["insert_characterID"])
) {
    $currentUsername = $_POST["insert_username"];
    $characterID = $_POST["insert_characterID"];

    $db = get_pdo_connection();

    $deleteQuery = $db->prepare("DELETE FROM `Character` WHERE characterID = :characterID AND username = :username");
    $deleteQuery->bindParam(':characterID', $characterID, PDO::PARAM_INT);
    $deleteQuery->bindParam(':username', $currentUsername, PDO::PARAM_STR);

    if ($deleteQuery->execute()) {
        if ($deleteQuery->rowCount() > 0) {
            $insertMsg = "Character deleted successfully.";
        } else {
            $insertMsg = "No character found with that ID for the given username.";
        }
    } else {
        $insertMsg = "Error deleting character: " . print_r($deleteQuery->errorInfo(), true);
    }
}

require_once("main.php");
?>
<h2>Delete a Character</h2>

<?php
if (!empty($insertMsg)) {
    echo htmlspecialchars($insertMsg) . "<br>";
}
?>

<form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
    <label>Username: <input type="text" name="insert_username" required></label><br>
    <label>Character ID: <input type="number" name="insert_characterID" required></label><br>
    <input type="submit" name="insert" value="Delete Character">
</form>
</body>
</html>

