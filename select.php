<?php
require_once("main.php");
$insertMsg = "";
?>
<h2>What Character?</h2>
<form action="<?= $_SERVER['PHP_SELF']?>" method="GET">
    <label>Character ID: <input type="number" name="search_CID" placeholder="ex: 34"></label><br>
    <label>Username: <input type="text" name="search_username" placeholder="ex: pixel_crate"></label><br>
    <input type="submit" name="search" value="Search"><br>
</form>

<?php
if (isset($_GET["search"])) {
    $db = get_pdo_connection();
    $query = false;
//character needs backticks
    if (!empty($_GET["search_CID"])) {
        echo "Searching by CID...<br>";
        $query = $db->prepare("SELECT * FROM `Character` WHERE characterID LIKE :characterID");
        $query->bindParam(":characterID", $_GET["search_CID"], PDO::PARAM_INT);
    } elseif (!empty($_GET["search_username"])) {
        echo "Searching by Username...<br>";
        $query = $db->prepare("SELECT * FROM `Character` WHERE username LIKE :username");
        $searchName = "%" . $_GET["search_username"] . "%";
        $query->bindParam(":username", $searchName, PDO::PARAM_STR);
    } else {
        echo "Getting everything...<br>";
        $query = $db->prepare("SELECT * FROM `Character`");
    }

    if ($query) {
        if ($query->execute()) {
            $rows = $query->fetchAll(PDO::FETCH_ASSOC);
            echo makeTable($rows);
        } else {
            echo "Error executing select query:<br>";
            print_r($query->errorInfo());
        }
    } else {
        echo "Error preparing query:<br>";
        print_r($db->errorInfo());
    }
}
?>

