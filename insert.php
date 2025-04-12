<?php
require_once("config.php");
$insertMsg = "";

if (
      isset($_POST["insert"]) &&
      !empty($_POST["insert_username"]) &&
      !empty($_POST["insert_email"]) &&
      !empty($_POST["insert_password"]) &&
      !empty($_POST["insert_birthday"]) &&
      !empty($_POST["insert_AdminID"])
    ) {
    $username = htmlspecialchars($_POST["insert_username"]);
    $email = htmlspecialchars($_POST["insert_email"]);
    $password = htmlspecialchars($_POST["insert_password"]);
    $birthday = htmlspecialchars($_POST["insert_birthday"]);
    $adminid = htmlspecialchars($_POST["insert_AdminID"]);

    $db = get_pdo_connection();
    $query = $db->prepare("insert into User (username, email, password, DOB)
                          values (:username, :email, :password, :DOB)");
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->bindParam(':DOB', $birthday, PDO::PARAM_STR);
    $query->bindParam(':AdminID', $adminid, PDO::PARAM_STR);

    if (!$query->execute()) {    
        $insertMsg =  "Error executing insert query:<br>" . print_r($query->errorInfo(), true);
    }
    else {
        $insertMsg = "Inserted " . $query->rowCount() . " rows";
    }
}

require_once("main.php");

?>

<h2>Create An Admin User</h2>

<?php

if (!empty($insertMsg)) {
    echo "$insertMsg<br>";
    $insertMsg = "";
}

?>
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
        <label>username: <input type="text" name="insert_username"></label><br>
        <label>email: <input type="email" name="insert_email"></label><br>
        <label>password: <input type="password" name="insert_password"></label><br>
        <label>birthday: <input type="date" name="insert_birthday"></label><br>
        <label>admin ID: <input type="number" name="insert_adminID"></label><br>
        <input type="submit" name="submit" value="Submit">
    </form>
