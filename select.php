<?php
require_once("main.php");
$insertMsg = "";
?>
    <h2>What Character?</h2>
    <form action="<?= $_SERVER['PHP_SELF']?>" method="GET">
        <label> Character ID: <input type="number" name="search_CID" 
                placeholder="ex: 34"></label><br>
        <label>Username: <input type="string" name="search_username"
                placeholder="ex: pixel_crate"></label><br>
        <input type="submit" name="search"><br>
    </form>

<?php
if (isset($_GET["search"])) {

    $db = get_pdo_connection();
    $query = false;

    if (!empty($_GET["search_CID"])) {
        echo "searching by CID....<br>";
        $query = $db->prepare("select * from Character where characterID = :characterID");
        $query->bindParam(":characterID", $_GET["search_CID"], PDO::PARAM_STR);
    }

    else if (!empty($_GET["search_username"])) {
        echo "searching by Username..<br>";
        $query = $db->prepare("select * from Character where username = :username");
        $query->bindValue(":username", "%" . $_GET["search_username"] . "%", PDO::PARAM_STR);
    }

    else {
        echo "getting everything...";
        $query = $db->prepare("select * from Character");
    }

    if ($query) {
        if ($query->execute()) {
            $rows = $query->fetchAll(PDO::FETCH_ASSOC);
            echo makeTable($rows);
        }
        else {
            echo "Error executing select query:<br>";
            print_r($query->errorInfo());
        }
    }

    else {
        echo "Error executing select query:<br>";
        print_r($db->errorInfo());
    }
}
?>
