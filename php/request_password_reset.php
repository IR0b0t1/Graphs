<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include("cfg.php");

require __DIR__ . '/../vendor/autoload.php';

if (!isset($_POST['email'])) {
    http_response_code(400);
    echo 'Brak adresu email.';
    exit;
}

$email = trim($_POST['email']);

$emailRegex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
if (!preg_match($emailRegex, $email)) {
    echo 'Jeśli podany email istnieje, wysłaliśmy link do resetu hasła.';
    exit;
}

$sql = "SELECT ID FROM Users WHERE Login = :email AND IsVerified = 1 LIMIT 1";
$stmt = $dbh->prepare($sql);
$stmt->execute([':email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Zawsze zwracamy taki sam komunikat, aby nie ujawniać czy email istnieje
if (!$user) {
    echo 'Jeśli podany email istnieje, wysłaliśmy link do resetu hasła.';
    exit;
}

$resetToken = bin2hex(random_bytes(32));
$expiresAt = date('Y-m-d H:i:s', time() + 3600); // 1 godzina ważności

$updateSql = "UPDATE Users SET PasswordResetToken = :token, PasswordResetExpires = :expires WHERE ID = :id";
$updateStmt = $dbh->prepare($updateSql);
$updateStmt->execute([
    ':token' => $resetToken,
    ':expires' => $expiresAt,
    ':id' => $user['ID']
]);

try {
    $mailer = new PHPMailer(true);

    $mailer->isSMTP();
    $mailer->Host       = 'smtp.your-mail-host.com';
    $mailer->SMTPAuth   = true;
    $mailer->Username   = 'your-smtp-username';
    $mailer->Password   = 'your-smtp-password';
    $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mailer->Port       = 587;

    $mailer->setFrom('no-reply@your-domain.com', 'Graphs');
    $mailer->addAddress($email);

    $resetLink = sprintf(
        'http://%s/Graphs/index.php?resetToken=%s',
        $_SERVER['HTTP_HOST'],
        urlencode($resetToken)
    );

    $mailer->isHTML(true);
    $mailer->Subject = 'Reset hasła w aplikacji Graphs';
    $mailer->Body    = sprintf(
        'Cześć,<br><br>Kliknij w poniższy link, aby zresetować swoje hasło:<br><a href="%1$s">%1$s</a><br><br>Jeśli to nie Ty inicjowałeś reset hasła, zignoruj tę wiadomość.',
        $resetLink
    );

    $mailer->AltBody = "Cześć,\n\nWejdź w poniższy link, aby zresetować swoje hasło:\n$resetLink\n\nJeśli to nie Ty inicjowałeś reset hasła, zignoruj tę wiadomość.";

    $mailer->send();
} catch (Exception $e) {
    // Logowanie błędu można dodać wg potrzeb
}

echo 'Jeśli podany email istnieje, wysłaliśmy link do resetu hasła.';
?> 

