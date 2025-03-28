<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue</title>
    <link href="https://fonts.googleapis.com/css2?family=Luckiest+Guy&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Luckiest Guy', cursive;
        }

        body {
            background-color: black;
            color: white;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* HEADER
        header {
            background: black;
            padding: 15px;
            text-align: center;
            font-size: 24px;
            border-bottom: 2px solid yellow;
        }

        header a {
            text-decoration: none;
            color: yellow;
        }
*/
        /* MAIN CONTENT */
        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 20px;
        }

        .welcome-text {
            font-size: 36px;
            margin-bottom: 20px;
            transition: 0.3s;
        }

        .welcome-text:hover {
            color: yellow;
        }

        .image {
            width: 250px;
            transition: transform 0.3s ease-in-out;
        }

        .image:hover {
            transform: scale(1.1);
        }

        /* FOOTER 
        footer {
            background: black;
            text-align: center;
            padding: 15px;
            border-top: 2px solid yellow;
        }

        footer p {
            font-size: 14px;
            color: white;
            transition: 0.3s;
        }

        footer p:hover {
            color: yellow;
        }
*/
    </style>
</head>
<body>

    <!-- HEADER -->
    <?php require_once(__DIR__ . '/header.php'); ?>

    <!-- CONTENU PRINCIPAL -->
    <main>
        <h1 class="welcome-text">Bienvenue sur notre site !<br> Gestion de tournois</h1>
        <img src="images/Logo assm salamandre without blurred colors_yelloweye.png" alt="Bienvenue" class="image">
    </main>

    <!-- FOOTER -->
    <?php require_once(__DIR__ . '/footer.php'); ?>

</body>
</html>
