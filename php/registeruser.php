<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include("cfg.php");

require __DIR__ . '/../vendor/autoload.php';

if (!isset($_POST['emailregister'], $_POST['passwordregister'])) {
    die('Missing registration data');
}

$email = trim($_POST['emailregister']);
$password = $_POST['passwordregister'];

$log = fopen("../debug.txt", "a");

$emailRegex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
if (!preg_match($emailRegex, $email)) {
    fwrite($log, "\nInvalid email format");
    fclose($log);
    exit;
}

$passwordRegex = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/';
if (!preg_match($passwordRegex, $password)) {
    fwrite($log, "\nWeak password");
    fclose($log);
    exit;
}

$sql = "SELECT COUNT(*) FROM Users WHERE Login = :email";
$stmt = $dbh->prepare($sql);
$stmt->execute([':email' => $email]);

if ($stmt->fetchColumn() > 0) {
    fwrite($log, "\nEmail already registered");
    fclose($log);
    exit;
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$verificationToken = bin2hex(random_bytes(32));

$sql = "INSERT INTO Users (Login, Password, VerificationToken, IsVerified) VALUES (:email, :pass, :token, 0)";
$stmt = $dbh->prepare($sql);
$stmt->execute([
    ':email' => $email,
    ':pass'  => $passwordHash,
    ':token' => $verificationToken
]);

try {
    $mailer = new PHPMailer(true);

    $mailer->isSMTP();
    $mailer->Host       = 'smtp.gmail.com';
    $mailer->SMTPAuth   = true;
    $mailer->Username   = 'flitewka@gmail.com';
    $mailer->Password   = 'diqo adta rphp fovf';
    $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mailer->Port       = 587;

    $mailer->setFrom('flitewka@gmail.com', 'Graphs');
    $mailer->addAddress($email);

    $verifyLink = sprintf(
        'http://%s/Graphs/php/verify.php?token=%s',
        $_SERVER['HTTP_HOST'],
        urlencode($verificationToken)
    );

    $mailer->isHTML(true);
    $mailer->Subject = 'Potwierdź swoje konto w aplikacji Graphs';
    $mailer->Body    = sprintf(
        'Cześć,<br><br>Kliknij w poniższy link, aby potwierdzić swoje konto:<br><a href="%1$s">%1$s</a><br><br>Jeśli to nie Ty zakładałeś konto, zignoruj tę wiadomość.',
        $verifyLink
    );

    $mailer->AltBody = "Cześć,\n\nWejdź w poniższy link, aby potwierdzić swoje konto:\n$verifyLink\n\nJeśli to nie Ty zakładałeś konto, zignoruj tę wiadomość.";

    $mailer->send();
    fwrite($log, "\nVerification email sent to: $email");
} catch (Exception $e) {
    fwrite($log, "\nVerification email failed for $email: " . $e->getMessage());
}

fwrite($log, "\nUser registered successfully (pending verification): $email");
fclose($log);

header("Location: ../index.php");
exit;
?> 