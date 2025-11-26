<?php

$choixJoueur = "";
$choixOrdi = "";

$options = ["pierre", "feuille", "ciseaux", "lézard", "spock"];

function result (string $choixJoueur, string $choixOrdi): string {
    if ($choixJoueur === $choixOrdi) {
        return "Egalité ! dommage...";
    }

    $victoryConditions = [
        ["pierre", "ciseaux", "lézard"],
        ["feuille", "pierre", "spock"],
        ["ciseaux", "feuille", "lézard"],
        ["lézard", "feuille", "spock"],
        ["spock", "pierre", "ciseaux"],
    ];

    foreach ($victoryConditions as $condition) {
        if ($choixJoueur === $condition[0] && ($choixOrdi === $condition[1] || $choixOrdi === $condition[2])) {
            return "Victoire du joueur ! Bien joué !";
        }
    }
    return "Perdu ! Victoire de l'ordinateur...";
}


$choiceJoueur = $_GET["choix"] ?? "pas choisi";
if ($choixJoueur === "reset") {
    $choixJoueur = "";
    $choixOrdi = "";
}
$random = array_rand($options);
$choixOrdi = $options[$random];
$message = result ($choixJoueur, $choixOrdi);

$html = <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Pierre Feuille Ciseaux Lézard Spock</title>
    <style>
        * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            width: 90%;
            max-width: 600px;
        }
        
        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        h2 {
            margin: 10px 0;
            color: #555;
            font-size: 1.2em;
        }

        .result-message {
            margin: 20px 0;
            padding: 15px;
            border-radius: 8px;
            background-color: #e9ecef;
            font-size: 1.5em;
            font-weight: bold;
            color: #212529;
        }

        .options {
            margin: 20px 0;
        }
        
        a {
            padding: 12px 20px;
            border: none;
            border-radius: 50px; /* Forme de pilule */
            background-color: #007bff;
            color: white;
            font-weight: bold;
            font-size: 16px;
            margin: 5px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s, transform 0.1s;
        }

        a:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
        
        .reset-btn {
            background-color: #dc3545;
            margin-top: 20px;
        }
        .reset-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Jeu de la Théorie du Big Bang 🖖</h1>
        
        <div class="result-message">
            <h2>$message</h2>
        </div>
        
        <h2>Vous avez choisi : **$choiceJoueur**</h2>
        <h2>L'ordinateur a choisi : **$choixOrdi**</h2>

        <div class="options">
            <a href="?choix=pierre">Pierre 🪨</a>
            <a href="?choix=feuille">Feuille 📄</a>
            <a href="?choix=ciseaux">Ciseaux ✂️</a>
            <a href="?choix=lézard">Lézard 🦎</a>
            <a href="?choix=spock">Spock 🖖</a>
        </div>
        
        <a href="?choix=reset" class="reset-btn">RESET 🔄</a>
    </div>
</body>
</html>
HTML;

echo $html;