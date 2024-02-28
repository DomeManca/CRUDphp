<?php
// Avvia la sessione
session_start();

// Include il file di configurazione del database
include_once 'config.php';

// Controlla se il modulo di registrazione è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ottieni i valori inseriti dall'utente nel modulo di registrazione
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query per inserire l'utente nel database
    $query = "INSERT INTO users (username, password) VALUES (:username, :password)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);

    // Esegui la query
    if ($stmt->execute()) {
        // Se l'inserimento è riuscito, reindirizza alla pagina di login
        header('Location: index.php');
    } else {
        // Se si è verificato un errore, mostra un messaggio di errore
        echo "Registration failed";
        sleep(5);
        header('Location: register.php');
    }
}
?>
