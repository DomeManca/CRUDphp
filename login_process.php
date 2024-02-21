<?php
// Avvia la sessione
session_start();

// Include il file di configurazione del database
include_once 'config.php';

// Controlla se il modulo di login è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ottieni i valori inseriti dall'utente nel modulo di login
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query per verificare l'esistenza dell'utente nel database
    $query = "SELECT * FROM users WHERE username = :username AND password = :password";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Se l'utente esiste, reindirizza alla pagina di dashboard
    if ($user) {
        $_SESSION['username'] = $username;
        header('Location: dashboard.php');
    } else {
        // Se l'utente non esiste, mostra un messaggio di errore
        $error = "Invalid username or password";
    }
}
?>
