<?php
require_once("config.php");
$insertMsg = "";

if (
      isset($_POST["insert"]) &&
      !empty($_POST["insert_username"]) &&
      !empty($_POST["insert_email"]) &&
      !empty($_POST["insert_password"]) &&
      !empty($_POST["insert_birthday"])
    ) {
    $usernameToInsert = htmlspecialchars($_POST["insert_username"]);
    $emailToInsert = htmlspecialchars($_POST["insert_email"]);
    $passwordToInsert = htmlspecialchars($_POST["insert_password"]);
    $birthdayToInsert = htmlspecialchars($_POST["insert_birthday"]);

    $db = get_pdo_connection();
    $query = $db->prepare("insert into User (username, email, password, DOB)
                          values (:username, :email, :password, :DOB)");
    $query->bindParam(':username', $usernameToInsert, PDO::PARAM_STR);
    $query->bindParam(':email', $emailToInsert, PDO::PARAM_STR);
    $query->bindParam(':password', $passwordToInsert, PDO::PARAM_STR);
    $query->bindParam(':DOB', $birthdayToInsert, PDO::PARAM_STR);

    if (!$query->execute()) {    
        $insertMsg =  "Error executing insert query:<br>" . print_r($query->errorInfo(), true);
    }
    else {
        $insertMsg = "Inserted " . $query->rowCount() . " rows";
    }
}

require_once("main.php");

?>

<h2>Create A User</h2>

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
        <input type="submit" name="submit" value="Submit">
    </form>
