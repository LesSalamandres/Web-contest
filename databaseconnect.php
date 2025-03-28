<?php
// Connexion à MySQL

try {
    // Connexion à MySQL sans spécifier de base de données
    $conn = new PDO("mysql:host=$servername", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Créer la base de données si elle n'existe pas
    $sql = "CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $conn->exec($sql);

    // Connexion à la base de données nouvellement créée
    $conn = new PDO("mysql:host=$servername;dbname=$database;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    echo "Base de données '$database' créée et connectée avec succès !";
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>