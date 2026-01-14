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
                            echo "
                            <form action='php/signout.php'>
                                <button class='logo-login'><!--<i class='fa-solid fa-gears'></i>--> {$row['login']}</button>
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
                <form method='POST' <?php
                    if(isset($_SESSION['userID'])) {
                        echo "class='hidden'";
                    }
                ?> id='registerbox' action='php/registeruser.php'>
                    <img src='gfx/register.webp' alt='Rejestracja' class='form-image'>
                    <input type='email' name='emailregister' placeholder='Email' required autocomplete='off'>
                    <input type='password' name='passwordregister' placeholder='Hasło' required autocomplete='off'>
                    <button class='register-button' type='submit' onclick='registerUser()'>Zarejestruj mnie</button>
                </form>
                <form method='POST' <?php
                    if(isset($_SESSION['userID'])) {
                        echo "class='hidden'";
                    }
                ?> id='loginbox' action='php/loginuser.php'>
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

    <script>
        const loginPage = () => {
            console.log('loginPage changed');
            document.getElementById('loginbox').className = 'formbox';
            document.getElementById('registerbox').className = 'hidden';
            document.getElementById('login-page-button').className = 'active';
            document.getElementById('register-page-button').className = 'inactive';
        };

        const registerPage = () => {
            console.log('registerPage changed');
            document.getElementById('registerbox').className = 'formbox';
            document.getElementById('loginbox').className = 'hidden';
            document.getElementById('register-page-button').className = 'active';
            document.getElementById('login-page-button').className = 'inactive';
        };

        // Fetch grafu
        const getData = async () => {
            if(document.getElementById('container')) {
                console.log('Deleting inner HTML in container');
                document.getElementById('container').innerHTML = '';
                console.log('Adding image to container');
                console.log(window.innerHeight)
                document.getElementById('container').innerHTML = `<img src='php/graph.php?width=1000&height=${window.innerHeight - 245}&margin=100&days=20&t=' + Math.random() + ' alt='Temperatura' usemap='#graphmap'>`;
                console.log('Fetching image map data');
                await fetch(`php/getdata.php?width=1000&height=${window.innerHeight - 245}&margin=100&days=20`)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('container').innerHTML += data;
                    })
                    .catch(error => console.error('Error fetching data:', error));
                console.log('Fetching data complete');
            } else {
                console.log('User not registered, no container exists');
            }
        };

        async function updateData(day, temperature, is_illness, is_done) {
            await fetch(`php/postdata.php?day=${day}&temperature=${temperature}&isIllness=${is_illness}&isDone=${is_done}`);
        }

        var day = 0;
        var temperature = 0.0;
        var is_illness = false;
        var is_done = false;
        var id = 0;

        const dialog = document.createElement('dialog');
        dialog.className = 'dialog-window';
        dialog.id = 'temperatureDialog'

        const form_container = document.createElement('div');
        form_container.className = 'form-container';

        const edit_form = document.createElement('form');
        edit_form.method = 'POST';
        edit_form.className = 'dialog-form';

        const day_label = document.createElement('label');

        const temperature_input = document.createElement('input');
        temperature_input.className = 'temperature-input';
        temperature_input.type = 'number';
        temperature_input.name = 'temperature-new';
        temperature_input.max = 37;
        temperature_input.min = 36;

        const save_button = document.createElement('button');
        save_button.className = 'form-button';
        save_button.innerText = 'Zapisz temperaturę';
        const ill_button = document.createElement('button');
        ill_button.className = 'form-button';
        ill_button.innerText = 'Choroba';
        const no_data_button = document.createElement('button');
        no_data_button.className = 'form-button';
        no_data_button.innerText = 'Brak pomiaru';

        save_button.addEventListener('click', (e) => {
            e.preventDefault();
            updateData(day, temperature_input.value, false, true);
            dialog.close();
            getData();
        });
        ill_button.addEventListener('click', (e) => {
            e.preventDefault();
            updateData(day, temperature_input.value, true, true);
            dialog.close();
            getData();
        });
        no_data_button.addEventListener('click', (e) => {
            e.preventDefault();
            updateData(day, 0, false, false);
            dialog.close();
            getData();
        });

        const close_form = document.createElement('form');
        close_form.method = 'dialog';
        close_form.className = 'dialog-form';

        const close_button = document.createElement('button');
        close_button.className = 'form-button';
        close_button.innerText = 'Zamknij';

        edit_form.appendChild(day_label);
        edit_form.appendChild(temperature_input);
        edit_form.appendChild(save_button);
        edit_form.appendChild(ill_button);
        edit_form.appendChild(no_data_button);
        form_container.appendChild(edit_form);
        close_form.appendChild(close_button);
        form_container.appendChild(close_form);
        dialog.appendChild(form_container)
        document.body.appendChild(dialog);

        const nodeClicked = (day, temperature, is_illness, is_done, id) => {
            console.log('Click');
            day_label.innerText = `Dzień ${day}`;
            dialog.showModal();

            this.id = id;
            this.day = day;
            this.temperature = temperature;

            if (is_illness == 1)
                this.is_illness = true;
            else
                this.is_illness = false

            if (is_done == 1)
                this.is_done = true;
            else
                this.is_done = false
        }

        getData();
    </script>
    <?php
    if(!isset($_SESSION['userID'])) { 
        echo "
        <script>
            loginPage();
        </script>";
    }
    ?>
</body>

</html>