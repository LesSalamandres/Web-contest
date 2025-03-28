<?php
    session_start();
    require_once(__DIR__ . '/functions.php');
?>
<?php
   
// Vérifier si le fichier a été téléchargé et s'il n'y a pas d'erreur
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["csv_file"])) {
    $uploadDir = "uploads/";  // Répertoire de destination du fichier uploadé
    $outputDir = "output/";   // Répertoire où les fichiers séparés seront enregistrés

    // Création des répertoires si non existants
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    if (!is_dir($outputDir)) mkdir($outputDir, 0777, true);

    $filePath = $uploadDir . basename($_FILES["csv_file"]["name"]);

    // Vérification et déplacement du fichier uploadé
    if (move_uploaded_file($_FILES["csv_file"]["tmp_name"], $filePath)) {
        echo "Fichier uploadé avec succès !<br>";

        // Lecture du fichier CSV avec séparation par ";"
        $rows = array_map(fn($line) => str_getcsv($line, ";"), file($filePath)); 
        $header = array_shift($rows); // Récupération des en-têtes
        $totalRows = count($rows); // Nombre total de lignes

        // Définir les index des colonnes à inclure dans chaque fichier
        $columnsFichier1IndexesJ1 = [0, 1, 2, 3]; // Colonnes : Nom J1, Prénom J1, LIC J1, Club J1
        $columnsFichier1IndexesJ2 = [4, 5, 6, 7]; // Colonnes : Nom J2, Prénom J2, LIC J2, Club J2
        $columnsFichier2Indexes = [2, 6, 8, 9]; // Colonnes : LIC J1, LIC J2, PTS INS EQ, PTS TEC EQ

        // Vérifier que les indices ne dépassent pas le nombre de colonnes dans le fichier
        $maxIndex = count($header) - 1;
        foreach (array_merge($columnsFichier1IndexesJ1, $columnsFichier1IndexesJ2, $columnsFichier2Indexes) as $index) {
            if ($index > $maxIndex) {
                echo "Erreur : L’index de colonne $index est hors limites.";
                exit;
            }
        }

        // Définir les nouveaux en-têtes pour table_player.csv
        $headerPlayer = ["nom", "prenom", "licence", "club"];

        // Ajouter les en-têtes aux fichiers de sortie
        $tablePlayer = [$headerPlayer];

        // Séparer les joueurs J1 et J2 en deux lignes distinctes
        foreach ($rows as $row) {
            // Récupérer les données J1
            $rowJ1 = array_map(fn($i) => $row[$i] ?? '', $columnsFichier1IndexesJ1);
            $tablePlayer[] = $rowJ1;

            // Récupérer les données J2
            $rowJ2 = array_map(fn($i) => $row[$i] ?? '', $columnsFichier1IndexesJ2);
            $tablePlayer[] = $rowJ2;
        }


        // Extraire les en-têtes selon les indices
        $headerFichier2 = array_map(fn($i) => $header[$i], $columnsFichier2Indexes);
         

        // Ajouter les en-têtes aux fichiers de sortie
        $tableTeam = [$headerFichier2];

        // Séparer les lignes dans les deux fichiers en fonction des colonnes spécifiées
        foreach ($rows as $row) {
            $tableTeam[] = array_map(fn($i) => $row[$i] ?? '', $columnsFichier2Indexes);
            
        }

        // Enregistrement des fichiers séparés dans le répertoire de sortie
        file_put_contents($outputDir . "table_player.csv", arrayToCsv($tablePlayer));
        file_put_contents($outputDir . "table_team.csv", arrayToCsv($tableTeam));

        // Affichage des liens pour télécharger les fichiers générés
        echo "Fichiers générés : <a href='$outputDir/table_player.csv'>table_player.csv</a> | <a href='$outputDir/table_team.csv'>table_team.csv</a>";
        header("Location: import_csv.php");// Redirection vers un autre script après la génération mise à jour base des joueurs
        exit();
        } else {
        echo "Erreur lors de l'upload.";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload CSV</title>
</head>
<body>
    <?php require_once(__DIR__ . '/header.php'); ?>
    <h2>Uploader un fichier CSV</h2>
    <form action="upload_csv.php" method="post" enctype="multipart/form-data">
        <input type="file" name="csv_file" accept=".csv" required>
        <button type="submit">Envoyer</button>
    </form>
    <?php require_once(__DIR__ . '/footer.php'); ?>
</body>
</html>
