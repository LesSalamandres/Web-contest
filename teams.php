<?php
session_start();
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/functions.php');

try {
    // Requête SQL pour récupérer les équipes
    $sql = "SELECT 
            CONCAT(j1.nom, '-', j2.nom) AS equipe,
            CONCAT(j1.prenom, ' ', j1.nom) AS joueur1,
            CONCAT(j2.prenom, ' ', j2.nom) AS joueur2,
            t.pts_ins_eq AS points_ins,
            t.pts_tec_eq AS points_tec
        FROM tableteamcontest t
        JOIN tableplayercontest j1 ON t.lic_j1 = j1.licence
        JOIN tableplayercontest j2 ON t.lic_j2 = j2.licence";

    // Exécution de la requête
    $stmt = $conn->query($sql);
} catch (PDOException $e) {
    echo "❌ Erreur de requete : " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des équipes</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid black; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

<h2>Liste des équipes</h2>
<table>
    <tr>
        <th>Équipe</th>
        <th>Joueur 1</th>
        <th>Joueur 2</th>
        <th>Points inscription équipe</th>
        <th>Points techniques équipe</th>
    </tr>
    <?php
    // Vérifie si des résultats sont trouvés avec PDO
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                    <td>{$row['equipe']}</td>
                    <td>{$row['joueur1']}</td>
                    <td>{$row['joueur2']}</td>
                    <td>{$row['points_ins']}</td>
                    <td>{$row['points_tec']}</td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Aucune équipe trouvée.</td></tr>";
        }
    ?>
</table>

</body>
</html>
