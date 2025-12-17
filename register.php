<?php
include("cfg.php");
$c=$dbh->exec("set names utf8");

$myfile = fopen("debug.txt", "w") or die("Unable to open file!");

$email = $_POST['email'];
$password = $_POST['password'];

$data = $dbh->query("SELECT COUNT(ID) FROM Users WHERE Login='$email'");

$emailRegex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
if(preg_match($emailRegex, $email) === 0){
    fwrite($myfile, "register.php: Invalid email format.");
    exit;
}
if($data->fetchColumn()>0){
    fwrite($myfile, "register.php: This email is already registered.");
    exit;
} else {
    $passwordRegex = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/';

    if(preg_match($passwordRegex, $password) === 0){
        fwrite($myfile, "register.php: Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, and one digit.");
        exit;
    }
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    $c=$dbh->exec("INSERT INTO Users (Login, Password) VALUES ('$email', '$passwordHash')");
    fwrite($myfile, "register.php: New user registered successfully.");
}

header("Location: login.html");
?>