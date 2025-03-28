<?php
/**
 * Fonction pour convertir un tableau en format CSV avec séparation par ";"
 */
function arrayToCsv($array) {
    $csv = "";
    foreach ($array as $row) {
        $csv .= '"' . implode('";"', $row) . "\"\n";  // Remplacer la virgule par `;`
    }
    return $csv;
}

/**
 * Fonction pour créer une table dynamiquement à partir d'un fichier CSV en PDO
 */
function createTableFromCSV($conn, $filePath, $tableName) {
    if (!file_exists($filePath)) {
        echo "❌ Le fichier $filePath n'existe pas.<br>";
        return;
    }

    $handle = fopen($filePath, "r");
    if ($handle === FALSE) {
        die("❌ Erreur lors de l'ouverture du fichier $filePath.<br>");
    }

    // Lire les en-têtes du fichier CSV
    $headers = fgetcsv($handle, 1000, ";");
    if (!$headers) {
        die("❌ Impossible de lire les en-têtes du fichier $filePath.<br>");
    }

    // Nettoyer les noms de colonnes (enlever espaces et caractères spéciaux)
    $columns = [];
    foreach ($headers as $col) {
        $col = strtolower(trim($col));
        $col = preg_replace('/[^a-z0-9_]/', '_', $col); // Remplace les caractères spéciaux par _
        $columns[] = "`$col` TEXT NOT NULL";
    }

    try {
        // Supprimer la table si elle existe
        $conn->exec("DROP TABLE IF EXISTS `$tableName`");

        // Créer la nouvelle table avec les colonnes du CSV
        $sql = "CREATE TABLE `$tableName` (id INT AUTO_INCREMENT PRIMARY KEY, " . implode(", ", $columns) . ")";
        $conn->exec($sql);

        echo "✅ Table '$tableName' créée avec succès.<br>";
    } catch (PDOException $e) {
        die("❌ Erreur lors de la création de la table $tableName : " . $e->getMessage());
    }

    fclose($handle);
}


/**
 * Fonction pour importer les données d'un CSV dans une table MySQL avec PDO
 */
function importCSV($conn, $filePath, $tableName) {
    if (!file_exists($filePath)) {
        echo "❌ Le fichier $filePath n'existe pas.<br>";
        return;
    }

    $handle = fopen($filePath, "r");
    if ($handle === FALSE) {
        die("❌ Erreur lors de l'ouverture du fichier $filePath.<br>");
    }

    // Lire les en-têtes du CSV
    $headers = fgetcsv($handle, 1000, ";");
    if (!$headers) {
        die("❌ Impossible de lire les en-têtes du fichier $filePath.<br>");
    }

    // Nettoyage des noms de colonnes
    $columns = [];
    foreach ($headers as $col) {
        $col = strtolower(trim($col));
        $col = preg_replace('/[^a-z0-9_]/', '_', $col); // Remplace les caractères spéciaux par _
        $columns[] = "`$col`";
    }

    // Préparation de l'insertion des données
    $placeholders = implode(",", array_fill(0, count($columns), "?"));
    $query = "INSERT INTO `$tableName` (" . implode(",", $columns) . ") VALUES ($placeholders)";
    $stmt = $conn->prepare($query);

    // Lire et insérer les données
    $rowCount = 0;
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        try {
            $stmt->execute($data);
            $rowCount++;
        } catch (PDOException $e) {
            echo "❌ Erreur lors de l'insertion d'une ligne : " . $e->getMessage() . "<br>";
        }
    }

    fclose($handle);
    echo "✅ Importation terminée : $rowCount lignes insérées dans '$tableName'.<br>";
}
?>

