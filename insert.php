<?php
require_once("config.php");
$insertMsg = "";

if (
    isset($_POST["submit"]) && 
    !empty($_POST["insert_username"]) &&
    !empty($_POST["insert_email"]) &&
    !empty($_POST["insert_password"]) &&
    !empty($_POST["insert_birthday"]) &&
    !empty($_POST["insert_adminID"]) 
) {
    $username = htmlspecialchars($_POST["insert_username"]);
    $email = htmlspecialchars($_POST["insert_email"]);
    $password = password_hash($_POST["insert_password"], PASSWORD_DEFAULT); 
    $birthday = htmlspecialchars($_POST["insert_birthday"]);
    $adminid = htmlspecialchars($_POST["insert_adminID"]);

    $db = get_pdo_connection();

    
    $query = $db->prepare("INSERT INTO User (username, email, password, DOB, AdminID)
                           VALUES (:username, :email, :password, :DOB, :AdminID)");

    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->bindParam(':DOB', $birthday, PDO::PARAM_STR);
    $query->bindParam(':AdminID', $adminid, PDO::PARAM_INT); 

    if (!$query->execute()) {
        $insertMsg = "Error executing insert query:<br>" . print_r($query->errorInfo(), true);
    } else {
        $insertMsg = "Inserted " . $query->rowCount() . " row(s).";
    }
}

require_once("main.php");
?>

<h2>Create An Admin User</h2>

<?php
if (!empty($insertMsg)) {
    echo htmlspecialchars($insertMsg) . "<br>";
}
?>


<form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
    <label>Username: <input type="text" name="insert_username" required></label><br>
    <label>Email: <input type="email" name="insert_email" required></label><br>
    <label>Password: <input type="password" name="insert_password" required></label><br>
    <label>Birthday: <input type="date" name="insert_birthday" required></label><br>
    <label>Admin ID: <input type="number" name="insert_adminID" required></label><br>
    <input type="submit" name="submit" value="Submit">
</form>
</body>
</html>

