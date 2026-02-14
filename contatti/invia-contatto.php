<?php
// Verifica che il form sia stato inviato tramite POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recupera e pulisce i dati
    $nome = htmlspecialchars(trim($_POST["nome"] ?? ''));
    $email = htmlspecialchars(trim($_POST["email"] ?? ''));
    $messaggio = htmlspecialchars(trim($_POST["messaggio"] ?? ''));

    // Costruisci la stringa da salvare
    $dati = "Nome: $nome\nEmail: $email\nMessaggio: $messaggio\n---\n";

    // Specifica il file di destinazione
    $file = "contatti_risposte.txt";

    // Salva i dati nel file
    if (file_put_contents($file, $dati, FILE_APPEND | LOCK_EX)) {
        echo "<h2>Grazie per averci contattato, $nome!</h2>";
        echo "<p>Ti risponderemo al più presto.</p>";
        echo "<a href='contatti.html'>Torna indietro</a>";
    } else {
        echo "<h2>Errore nel salvataggio dei dati.</h2>";
    }
} else {
    // Accesso non valido
    echo "<h2>Accesso non autorizzato.</h2>";
}
?>
