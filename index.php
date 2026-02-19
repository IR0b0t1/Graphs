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
    <script type="module" src="javascript/main.js" defer></script>
    <title>Graphs</title>
</head>

<body>
    <div class='container'>
        <nav>
            <div>
                <div class='logo-box'>
                    <img class='logo' src='gfx/logo.png' alt='Logo'>
                    <script src='https://kit.fontawesome.com/fadd1db071.js' crossorigin='anonymous'></script>
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

                            $query = 'SELECT graphNo FROM graphs WHERE UserID = :id LIMIT 1';
                            $stmt = $dbh->prepare($query);
                            $stmt->bindParam(':id', $_SESSION['userID'], PDO::PARAM_INT);
                            $stmt->execute();
                            $result = $stmt->fetchAll(PDO::FETCH_NUM);
                            $row = $result[0];
                            echo "<script>
                                document.addEventListener('DOMContentLoaded', () => {
                                    getData(".$row[0].");
                                });
                                </script>";
                            // echo $row[0];
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
                <div class='datagrid'<?php
                    if(!isset($_SESSION['userID'])) {
                        echo "style='display: none'";
                    } 
                ?> >
                    <div style='display: flex; justify-content: space-around; margin: 10px;'>
                        <button class='add-button' onclick='newRecordDialog()'>Dodaj pomiar</button>
                        <div class='graph-nav'>
                            <button class='graph-nav-button' onclick='previousGraph()'><</button>
                            <span class='graph-nav-text' id='graph-nav-text'>Wykres</span>
                            <button class='graph-nav-button' onclick='nextGraph()'>></button>
                        </div>
                        <button class='add-button' onclick='addNewGraph(1)'>Dodaj nowy wykres</button>
                    </div>
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
        </div>
        <footer>
            <p>Copyright &#169; 2025 by Filip L</p>
        </footer>
    </div>
    <dialog id='addRecordDialog' class='dialog-window'>
        <div class='form-container'>
            <form method='post' action='php/addrecord.php' class='dialog-form'>
                <h2>Dodaj nowy dzień</h2>
                <label for='temperature-new'>Temperatura</label>
                <input class='form-input' type='number' id='temperature-new' name='temperature-new'>
                <button type='submit' class='form-button'>Dodaj dzień</button>
                <button type='button' class='form-button' onclick='addIllness()'>Choroba</button>
                <button type='button' class='form-button' onclick='addNotDone()'>Brak pomiaru</button>
                <button type='button' class='form-button' onclick='document.getElementById("addRecordDialog").close()'>Zamknij</button>
            </form>
        </div>
    </dialog>
</body>

</html>