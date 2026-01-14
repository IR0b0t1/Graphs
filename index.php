<?php
include('php/cfg.php');
session_start();
$c = $dbh->exec("set names utf8");
?>
<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link href='stylesheet/main.css' rel='stylesheet'>
    <script type="module" src="javascript/main.js"></script>
    <title>Rejestracja</title>
</head>

<body>
    <div class='container'>
        <nav>
            <div>
                <div class='logo-box'>
                    <img class='logo' src='gfx/logo.png' alt='Logo'>
                    <!-- <script src='https://kit.fontawesome.com/fadd1db071.js' crossorigin='anonymous'></script> -->
                     <?php
                        if(!isset($_SESSION['userID'])) {
                            echo "
                                <button class='logo-login' onclick='loginPage()'><!--<i class='fa-solid fa-gears'></i>--> Zaloguj się</button>
                            ";
                        } else {
                            $query = 'SELECT login FROM users WHERE ID = :id';
                            $stmt = $dbh->prepare($query);
                            $stmt->bindParam(':id', $_SESSION['userID'], PDO::PARAM_INT);
                            $stmt->execute();
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            $row = $result[0];
                            $login = explode('@', $row['login'])[0];
                            echo "
                            <form action='php/signout.php'>
                                <button class='logo-login'><!--<i class='fa-solid fa-gears'></i>--> {$login}</button>
                            </form>
                            ";
                        }
                     ?>
                    
                    <?php
                        if(!isset($_SESSION['userID'])) { 
                            echo "
                            <div class='navigation'>
                                <button id='login-page-button' onclick='loginPage()'>Zaloguj</button>
                                <button id='register-page-button' onclick='registerPage()'>Utwórz konto</button>
                            </div>";
                        }
                    ?>
                </div>
            </div>
            <p id='returnMessage'></p>
        </nav>
        <div class='content'>
            <div class='flex-center'>
                <form method='POST' class='<?php
                    if(isset($_SESSION['userID'])) {
                        echo "hidden";
                    } else {
                        echo "hidden";
                    }
                ?>' id='registerbox' action='php/registeruser.php'>
                    <img src='gfx/register.webp' alt='Rejestracja' class='form-image'>
                    <input type='email' name='emailregister' placeholder='Email' required autocomplete='off'>
                    <input type='password' name='passwordregister' placeholder='Hasło' required autocomplete='off'>
                    <button class='register-button' type='submit' onclick='registerUser()'>Zarejestruj mnie</button>
                </form>
                <form method='POST' class='<?php
                    if(isset($_SESSION['userID'])) {
                        echo "hidden";
                    } else {
                        echo "formbox";
                    }
                ?>' id='loginbox' action='php/loginuser.php'>
                    <img src='gfx/login.webp' alt='Logowanie' class='form-image'>
                    <input type='email' name='emaillogin' placeholder='Email' required autocomplete='off'>
                    <input type='password' name='passwordlogin' placeholder='Hasło' required autocomplete='off'>
                    <div class='button-box'>
                        <button class='login-button' type='submit' onclick='loginUser()'>Zaloguj</button>
                        <a href='#' class='password-forgot'>Zapomniałem hasła</a>
                    </div>
                </form>
                <?php 
                if(isset($_SESSION['userID'])) {
                    echo "
                        <div id='container'>
                        </div>
                    ";
                }
                ?>
            </div>
        </div>
        <footer>
            <p>Copyright &#169; 2025 by Filip L</p>
        </footer>
    </div>
</body>

</html>