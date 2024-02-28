<?php
session_start();
include_once 'config.php';

// Verifica se l'utente è autenticato
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

// Funzione per recuperare gli allenatori dal database
function getAllenatori() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM Allenatore");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Funzione per recuperare le squadre dal database
function getSquadre() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM Squadra");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Funzione per recuperare tutti i giocatori dal database
function getAllGiocatori() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM Giocatore");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Popola le variabili $allenatori e $squadre
$allenatori = getAllenatori();
$squadre = getSquadre();

// Visualizzazione dei dati totali
$allenatori = getAllenatori();

// Aggiunta dati
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $dataNascita = $_POST['data_nascita'];
    $squadraId = $_POST['squadra_id'];

    $query = "INSERT INTO Giocatore (Nome, Cognome, Data_di_Nascita, Squadra_ID) VALUES (:nome, :cognome, :dataNascita, :squadraId)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cognome', $cognome);
    $stmt->bindParam(':dataNascita', $dataNascita);
    $stmt->bindParam(':squadraId', $squadraId);
    $stmt->execute();
    header('Location: dashboard.php');
    exit();
}
// Modifica dati
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
    $giocatoreId = $_POST['giocatore_id'];
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $dataNascita = $_POST['data_nascita'];

    $query = "UPDATE Giocatore SET Nome = :nome, Cognome = :cognome, Data_di_Nascita = :dataNascita WHERE ID = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cognome', $cognome);
    $stmt->bindParam(':dataNascita', $dataNascita);
    $stmt->bindParam(':id', $giocatoreId);
    $stmt->execute();
    header('Location: dashboard.php');
    exit();
}

// Cancellazione dei dati
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $giocatoreId = $_POST['giocatore_id'];
    $query = "DELETE FROM Giocatore WHERE ID = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $giocatoreId);
    $stmt->execute();
    header('Location: dashboard.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Includi il CSS di Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 20px;
        }
        .container {
            max-width: 80%; 
        }
        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Benvenuto, <?php echo $_SESSION['username']; ?>!</h2>
        
        <!-- Visualizzazione con filtri -->
<div class="card mb-4">
    <div class="card-header">
        Visualizza Giocatori-Squadra-Allenatore
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="form-group">
                <label for="squadra_id">Seleziona una squadra:</label>
                <select class="form-control" name="squadra_id" id="squadra_id">
                    <option value="">Tutte le squadre</option>
                    <?php
                    // Esegui una query per ottenere tutte le squadre
                    $stmt = $pdo->query("SELECT * FROM Squadra");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . $row['ID'] . "'>" . $row['Nome'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Applica filtro</button>
        </form>
        <br>
        <table class="table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Data di Nascita</th>
                    <th>Squadra Nome</th>
                    <th>Squadra Anno di Nascita</th>
                    <th>Squadra Città</th>
                    <th>Allenatore Nome</th>
                    <th>Allenatore Cognome</th>
                    <th>Allenatore Data di Nascita</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Verifica se è stato inviato un filtro per squadra
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['squadra_id']) && $_POST['squadra_id'] != "") {
                    $squadra_id = $_POST['squadra_id'];
                    $query = "SELECT Giocatore.Nome AS Giocatore_Nome, Giocatore.Cognome AS Giocatore_Cognome, Giocatore.Data_di_Nascita AS Giocatore_Data_di_Nascita, Squadra.Nome AS Squadra_Nome, Squadra.Anno_Nascita AS Squadra_Anno_Nascita,  Squadra.Citta AS Squadra_Citta, Allenatore.Nome AS Allenatore_Nome, Allenatore.Cognome AS Allenatore_Cognome,  Allenatore.Data_di_Nascita AS Allenatore_Data_di_Nascita FROM Giocatore LEFT JOIN Squadra ON Giocatore.Squadra_ID = Squadra.ID LEFT JOIN Allenatore ON Squadra.Allenatore = Allenatore.ID LEFT JOIN Contratto ON Squadra.ID = Contratto.Squadra_ID LEFT JOIN Sponsor ON Contratto.Sponsor_ID = Sponsor.ID WHERE Squadra.ID = :squadra_id";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':squadra_id', $squadra_id, PDO::PARAM_INT);
                    $stmt->execute();
                } else {
                    // Nessun filtro, visualizza tutti i giocatori
                    $stmt = $pdo->query("SELECT Giocatore.Nome AS Giocatore_Nome, Giocatore.Cognome AS Giocatore_Cognome, Giocatore.Data_di_Nascita AS Giocatore_Data_di_Nascita, Squadra.Nome AS Squadra_Nome, Squadra.Anno_Nascita AS Squadra_Anno_Nascita,  Squadra.Citta AS Squadra_Citta, Allenatore.Nome AS Allenatore_Nome, Allenatore.Cognome AS Allenatore_Cognome,  Allenatore.Data_di_Nascita AS Allenatore_Data_di_Nascita FROM Giocatore LEFT JOIN Squadra ON Giocatore.Squadra_ID = Squadra.ID LEFT JOIN Allenatore ON Squadra.Allenatore = Allenatore.ID LEFT JOIN Contratto ON Squadra.ID = Contratto.Squadra_ID LEFT JOIN Sponsor ON Contratto.Sponsor_ID = Sponsor.ID");
                }

                // Loop attraverso i risultati e stampa le righe della tabella
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . $row['Giocatore_Nome'] . "</td>";
                    echo "<td>" . $row['Giocatore_Cognome'] . "</td>";
                    echo "<td>" . $row['Giocatore_Data_di_Nascita'] . "</td>";
                    echo "<td>" . $row['Squadra_Nome'] . "</td>";
                    echo "<td>" . $row['Squadra_Anno_Nascita'] . "</td>";
                    echo "<td>" . $row['Squadra_Citta'] . "</td>";
                    echo "<td>" . $row['Allenatore_Nome'] . "</td>";
                    echo "<td>" . $row['Allenatore_Cognome'] . "</td>";
                    echo "<td>" . $row['Allenatore_Data_di_Nascita'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
 
<?php
// Query per recuperare le informazioni su squadre e sponsor
$query = "SELECT squadra.Nome AS Squadra_Nome, squadra.Citta AS Squadra_Citta, GROUP_CONCAT(sponsor.Nome SEPARATOR ', ') AS Sponsor_Nomi, SUM(contratto.Cifra) AS Cifra_Totale
          FROM squadra
          LEFT JOIN contratto ON squadra.ID = contratto.Squadra_ID
          LEFT JOIN sponsor ON contratto.Sponsor_ID = sponsor.ID
          GROUP BY squadra.ID";
$stmt = $pdo->query($query);
$squadre_sponsor = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Visualizzazione squadre e sponsor -->
<div class="card mb-4">
    <div class="card-header">
        Squadre e Sponsor
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Squadra</th>
                    <th>Città</th>
                    <th>Sponsor</th>
                    <th>Cifra Totale</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($squadre_sponsor as $row): ?>
                    <tr>
                        <td><?php echo $row['Squadra_Nome']; ?></td>
                        <td><?php echo $row['Squadra_Citta']; ?></td>
                        <td><?php echo $row['Sponsor_Nomi']; ?></td>
                        <td><?php echo $row['Cifra_Totale']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

        <!-- Aggiunta dati -->
        <div class="card mb-4">
            <div class="card-header">
                Aggiungi giocatore
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="form-group">
                        <label for="nome">Nome:</label>
                        <input type="text" class="form-control" name="nome" required>
                    </div>
                    <div class="form-group">
                        <label for="cognome">Cognome:</label>
                        <input type="text" class="form-control" name="cognome" required>
                    </div>
                    <div class="form-group">
                        <label for="data_nascita">Data di nascita:</label>
                        <input type="date" class="form-control" name="data_nascita" required>
                    </div>
                    <div class="form-group">
                        <label for="squadra_id">Squadra:</label>
                        <select class="form-control" name="squadra_id" required>
                            <?php foreach ($squadre as $squadra): ?>
                                <option value="<?php echo $squadra['ID']; ?>"><?php echo $squadra['Nome']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" name="add">Aggiungi</button>
                </form>
            </div>
        </div>

        <?php
// Query per recuperare i giocatori
$query = "SELECT ID, CONCAT(Nome, ' ', Cognome) AS NomeCompleto FROM Giocatore";
$stmt = $pdo->query($query);
$giocatori = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Modifica dati -->
<div class="card mb-4">
    <div class="card-header">
        Modifica giocatore
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="form-group">
                <select class="form-control" name="giocatore_id" required>
                    <?php foreach ($giocatori as $giocatore): ?>
                        <option value="<?php echo $giocatore['ID']; ?>"><?php echo $giocatore['NomeCompleto']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" class="form-control" name="nome" required>
            </div>
            <div class="form-group">
                <label for="cognome">Cognome:</label>
                <input type="text" class="form-control" name="cognome" required>
            </div>
            <div class="form-group">
                <label for="data_nascita">Data di nascita:</label>
                <input type="date" class="form-control" name="data_nascita" required>
            </div>
            <button type="submit" class="btn btn-primary" name="edit">Modifica</button>
        </form>
    </div>
</div>

<!-- Cancellazione dati -->
<div class="card">
    <div class="card-header">
        Cancella giocatore
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="form-group">
                <select class="form-control" name="giocatore_id" required>
                    <?php foreach ($giocatori as $giocatore): ?>
                        <option value="<?php echo $giocatore['ID']; ?>"><?php echo $giocatore['NomeCompleto']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-danger" name="delete">Cancella</button>
        </form>
    </div>
</div>

        
        <p><a href="logout.php">Logout</a></p>
    </div>

    <!-- Includi il JavaScript di Bootstrap -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
