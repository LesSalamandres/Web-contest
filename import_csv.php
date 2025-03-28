<?php
session_start();
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/header.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/functions.php');

try {
    // 🔥 Étape 1 : Créer les tables à partir des CSV
    echo "🔄 Création des tables à partir des CSV...<br>";
    createTableFromCSV($conn, "output/table_player.csv", "tableplayercontest");
    createTableFromCSV($conn, "output/table_team.csv", "tableteamcontest");

    // 🔥 Étape 2 : Importer les données des CSV
    echo "🔄 Importation des données...<br>";
    importCSV($conn, "output/table_player.csv", "tableplayercontest");
    importCSV($conn, "output/table_team.csv", "tableteamcontest");

    // 🔥 Étape 3 : Créer la table `tableplayers` si elle n'existe pas
    $sqlCreate = "CREATE TABLE IF NOT EXISTS tableplayers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(255) NOT NULL,
        prenom VARCHAR(255) NOT NULL,
        licence VARCHAR(50) NOT NULL UNIQUE, 
        club VARCHAR(255) NOT NULL,
        ptsIns INT(5) NOT NULL,
        ptsTec INT(5) NOT NULL,
        commentaires TEXT NOT NULL
    )";
    $conn->exec($sqlCreate);
    echo "✅ Table 'tableplayers' prête.<br>";

    // 🔥 Étape 4 : Insérer uniquement les nouvelles données (évite les doublons sur `licence`)
    $sqlInsert = "INSERT IGNORE INTO tableplayers (nom, prenom, licence, club)
                  SELECT nom, prenom, licence, club FROM tableplayercontest";
    $conn->exec($sqlInsert);
    echo "✅ Copie réussie sans doublons.<br>";

    // 🔥 Étape 5 : Renuméroter les `id` à partir de 1
    // Réinitialiser l'auto-incrément
    $conn->exec("ALTER TABLE tableplayers AUTO_INCREMENT = 1");

    echo "✅ Les identifiants (id) de la table 'tableplayers' ont été réinitialisés.<br>";

} catch (PDOException $e) {
    die("❌ Erreur : " . $e->getMessage());
}

// ✅ La connexion PDO est fermée automatiquement en fin de script.
require_once(__DIR__ . '/footer.php');
?>
