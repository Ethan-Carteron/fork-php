<?php

$choixJoueur = "";
$choixOrdi = "";

$options = ["pierre", "feuille", "ciseaux"];

function Perdu (string $choixJoueur): string {
    if ($choixJoueur == "pierre") {
        return "feuille";
    }
    if ($choixJoueur == "feuille") {
        return "ciseaux";
    }
    if ($choixJoueur == "ciseaux") {
        return "pierre";
    }
    return "";
}

function aleatoire (string $options): string {
    $random = rand (0,2);
    return $options[$random];
}

function result (string $choixJoueur, string $choixOrdi): string {
    if ($choixJoueur === $choixOrdi) {
        return "Egalité ! dommage...";
    }

    $victoryConditions = [
        ["pierre", "ciseaux"],
        ["feuille", "pierre"],
        ["ciseaux", "feuille"],
    ];

    if ($choixJoueur === $victoryConditions[0] && $choixJoueur === $victoryConditions[1]) {
        return "Victoire du joueur ! Bien joué !";
    }
    return "Perdu ! Victoire de l'ordinateur...";
}

// $choixOrdi = aleatoire;sss
$choixOrdi = Perdu ($choixJoueur);
$choiceJoueur = $_POST["choix"];
$message = result ($choixJoueur, $choixOrdi);

$html = <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Pierre Feuille Ciseaux</title>
    <style>
        * {
        margin: 0;
        padding: 0;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            margin: 5px;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <form action="index.php" method="post">
        <button type="submit" name="choix" value="pierre">Pierre 🪨</button>
        <button type="submit" name="choix" value="feuille">Feuille 📄</button>
        <button type="submit" name="choix" value="ciseaux">Ciseaux ✂️</button>
    </form>
    
</body>
</html>
HTML;

echo $html;