<?php
// Script simple pour vérifier la présence des données dans classement_poule sans utiliser Doctrine ORM

$mysqli = new mysqli("127.0.0.1", "root", "", "gametournoi");

if ($mysqli->connect_errno) {
    echo "Erreur de connexion à la base de données : " . $mysqli->connect_error . PHP_EOL;
    exit(1);
}

$sql = "SELECT c.id, c.points, c.rang, club.nom as club_nom 
        FROM classement_poule c 
        JOIN club ON c.club_id = club.id 
        ORDER BY c.rang ASC";

if ($result = $mysqli->query($sql)) {
    echo "Nombre de classements trouvés : " . $result->num_rows . PHP_EOL;
    while ($row = $result->fetch_assoc()) {
        echo "Club: " . $row['club_nom'] . " - Points: " . $row['points'] . " - Rang: " . $row['rang'] . PHP_EOL;
    }
    $result->free();
} else {
    echo "Erreur lors de la requête SQL : " . $mysqli->error . PHP_EOL;
}

$mysqli->close();
?>
