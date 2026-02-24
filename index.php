<?php
include('php/cfg.php');
session_start();

$c = $dbh->exec("set names utf8");

$graphNo = isset($_GET['graphNo']) ? $_GET['graphNo'] : 1;
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
                            echo "<button class='logo-login' onclick='loginPage()'><i class='fa-solid fa-gears'></i> Zaloguj się</button>";
                            echo "<script>
                                document.addEventListener('DOMContentLoaded', () => {
                                    loginPage();
                                });
                                </script>";
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
                                <button class='logo-login'><i class='fa-solid fa-gears'></i> {$login}</button>
                            </form>
                            ";

                            echo "<script>
                                document.addEventListener('DOMContentLoaded', () => {
                                    getData(".$graphNo.");
                                });
                                </script>";

                            $query = "SELECT MAX(graphNo) FROM Graphs WHERE UserID = :userID";
                            $stmt = $dbh->prepare($query);
                            $stmt->execute([
                                ':userID' => $_SESSION['userID']
                            ]);
                            $result = $stmt->fetchAll(PDO::FETCH_NUM);
                            $maxGraphNo = $result[0][0];

                            $graphNo = isset($_GET['graphNo']) ? (int)$_GET['graphNo'] : 1;

                            if ($graphNo < 1) {
                                $graphNo = 1;
                            }

                            if ($graphNo > $maxGraphNo) {
                                $graphNo = $maxGraphNo;
                            }

                            $query = "SELECT ID, Name FROM Graphs WHERE GraphNo = :graphNo AND UserID = :userID";
                            $stmt = $dbh->prepare($query);
                            $stmt->execute([
                                ':graphNo'  => $graphNo,
                                ':userID' => $_SESSION['userID']
                            ]);
                            $result = $stmt->fetchAll(PDO::FETCH_NUM);
                            $graphID = $result[0][0];
                            $graphName = $result[0][1];

                            $query = "SELECT MAX(Day) FROM temperature WHERE GraphID = :graphID";
                            $stmt = $dbh->prepare($query);
                            $stmt->execute([
                                ':graphID' => $graphID
                            ]);
                            $result = $stmt->fetchAll(PDO::FETCH_NUM);
                            $maxDay = $result[0][0];
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
                        <button class='add-button' onclick='deleteLastRecord(<?php echo "$graphID"?>)'>Usuń ostatni pomiar</button>
                        <div class='graph-nav'>
                            <a class='graph-nav-button' href='<?php echo "index.php?graphNo=1";?>'>&lt;&lt;</a>
                            <a class='graph-nav-button'href='<?php echo ($graphNo > 1) ? "index.php?graphNo=".($graphNo-1) : "#";?>'>&lt;</a>
                            <span class='graph-nav-text' id='graph-nav-text'><?php echo "Wykres nr. $graphNo o nazwie $graphName";?></span>
                            <a class='graph-nav-button' href='<?php echo ($graphNo < $maxGraphNo) ? "index.php?graphNo=".($graphNo+1) : "#";?>'>&gt;</a>
                            <a class='graph-nav-button' href='<?php echo "index.php?graphNo=".$maxGraphNo;?>'>&gt;&gt;</a>
                        </div>
                        <button class='add-button' onclick='deleteGraph(<?php echo $graphNo;?>)'>Usuń wykres</button>
                        <button class='add-button' onclick='newGraphDialog()'>Dodaj nowy wykres</button>
                    </div>
                    <div>
                        <button class='add-button' onclick='exportGraphToPdf(<?php echo $graphNo?>)'>Wyeksportuj do PDF</button>
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
            <form class='dialog-form'>
                <h2>Dodaj nowy dzień</h2>
                <label for='temperature-new'>Temperatura</label>
                <input class='form-input' type='number' id='temperatureNew' name='temperature-new' min='36' max='37'>
                <button type='button' class='form-button' onclick='addRecord(<?php echo $graphNo?>)'>Dodaj dzień</button>
                <button type='button' class='form-button' onclick='addIllness(<?php echo $graphNo?>)'>Choroba</button>
                <button type='button' class='form-button' onclick='addNotDone(<?php echo $graphNo?>)'>Brak pomiaru</button>
                <button type='button' class='form-button' onclick='document.getElementById("addRecordDialog").close()'>Zamknij</button>
            </form>
        </div>
    </dialog>
    <dialog id='editTemperatureDialog' class='dialog-window'>
        <div class='form-container'>
            <form class='dialog-form'>
                <h2>Edytuj dzień</h2>
                <label id='dayLabel' for='editTemperatureInput'></label>
                <input type='number' name='temperature-change' id='editTemperatureInput' class='form-input' min='36' max='37'>
                <input type='hidden' name='temperature-graphno'id='editTemperatureGraphNo' <?php echo "value='".$graphNo."'";?>>
                <button type='button' class='form-button' id='editTemperatureSave'>Zapisz zmiany</button>
                <button type='button' class='form-button' id='editTemperatureIll'>Choroba</button>
                <button type='button' class='form-button' id='editTemperatureNoData'>Brak pomiaru</button>
                <button type='button' class='form-button' id='editTemperatureDelete'>Usuń dzień</button>
            </form>
            <form method='dialog' class='dialog-form'>
                <button type='submit' class='form-button' id='editTemperatureClose'>Zamknij</button>
            </form>
        </div>
    </dialog>
    <dialog id='addNewGraphDialog' class='dialog-window'>
        <div class='form-container'>
            <form class='dialog-form' method='post' action='php/addgraph.php'>
                <h2>Dodaj nowy wykres</h2>
                <label for='addGraphName'>Nazwa wykresu</label>
                <input type='text' name='graphName' id='addGraphName' class='form-input'>
                <label for='addGraphDays'>Ilość dni w wykresie</label>
                <input type='number' name='daysAmount' min='1' id='addGraphDays' class='form-input'>
                <button type='submit' class='form-button'>Dodaj wykres</button>
            </form>
            <form method='dialog' class='dialog-form'>
                <button type='submit' class='form-button' id='addGraphClose'>Zamknij</button>
            </form>
        </div>
    </dialog>
</body>

</html>