<?php
$limiteMinuti = 30;
$logFile = __DIR__ . '/logs/ip_log.json';
$fileRisposte = __DIR__ . '/risposte_questionario.txt';

// Imposta fuso orario (opzionale ma consigliato)
date_default_timezone_set("Europe/Rome");

// Verifica reCAPTCHA
$secretKey = '6Le5DlErAAAAAKjJ5aBNw6XqDaSKBBjqojWdQnk9';
$captchaResponse = $_POST['g-recaptcha-response'] ?? '';
$remoteIp = $_SERVER['REMOTE_ADDR'] ?? '';

// Verifica tramite Google
$verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captchaResponse&remoteip=$remoteIp");
$captchaSuccess = json_decode($verify);

if (!$captchaSuccess || !$captchaSuccess->success) {
    die("<h2>Verifica CAPTCHA fallita. Torna indietro e riprova.</h2>");
}

// Blocco IP per 30 minuti
$ip = $remoteIp;
$timestamp = time();
$log = [];

if (file_exists($logFile)) {
    $contenuto = file_get_contents($logFile);
    $log = json_decode($contenuto, true) ?? [];

    if (isset($log[$ip]) && ($timestamp - $log[$ip]) < ($limiteMinuti * 60)) {
        $attesa = ceil(($limiteMinuti * 60 - ($timestamp - $log[$ip])) / 60);
        die("<h2>Hai già compilato il questionario di recente. Riprova tra $attesa minuti.</h2>");
    }
}

// Gestione dati POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = htmlspecialchars(trim($_POST["nome"] ?? ''));
    $eta = htmlspecialchars(trim($_POST["eta"] ?? ''));
    $piaciuto = htmlspecialchars(trim($_POST["piaciuto"] ?? ''));
    $commenti = htmlspecialchars(trim($_POST["commenti"] ?? ''));
    $storia = htmlspecialchars(trim($_POST["storia"] ?? ''));
    $aspetti = isset($_POST["aspetti"]) ? implode(", ", array_map('htmlspecialchars', $_POST["aspetti"])) : 'N/D';

    $data = date("Y-m-d H:i:s");

    $dati = <<<EOD
Data: $data
IP: $ip
Nome: $nome
Età: $eta
Ti è piaciuto?: $piaciuto
Aspetti preferiti: $aspetti
Conosci la storia?: $storia
Commenti: $commenti
---

EOD;

    if (file_put_contents($fileRisposte, $dati, FILE_APPEND | LOCK_EX)) {
        // Salva timestamp IP
        $log[$ip] = $timestamp;
        file_put_contents($logFile, json_encode($log));
        header("Location: grazie.html");
        exit;
    } else {
        echo "<h2>Errore nel salvataggio. Riprova.</h2>";
    }
} else {
    echo "<h2>Accesso non autorizzato.</h2>";
}
?>
