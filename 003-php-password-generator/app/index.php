<?php

$size = $_POST ["size"] ?? 12;
$isMin = $_POST ["use-alpha-min"] ?? 0;
$isMaj = $_POST ["use-alpha-maj"] ?? 0;
$isNum = $_POST ["use-num"] ?? 0;
$isSpe = $_POST ["use-symbols"] ?? 0;

$motDePasse = "";

function generateSelectOptions($selected): string
{
    $htmlGenerator = "";

    $options = range(8, 42);

    foreach ($options as $value) {
        $attribute = "";
        if ((int) $value == (int) $selected) {
            $attribute = "selected";
        }

        $htmlGenerator .= "<option $attribute value=\"$value\">$value</option>";
    }
    return $htmlGenerator;
}

function creationListes(int $isMin, int $isMaj, int $isNum, int $isSpe):array {

    $minuscules = [];
    if ($isMin == 1) {
        $minuscules = range ("a", "z");
    }

    $majuscules = [];
    if ($isMaj == 1) {
        $majuscules = range ("A", "Z");
    }

    $chiffres = [];
    if ($isNum == 1) {
        $chiffres = range (0,9);
    }

    $caracteresSpeciaux = [];
    if ($isSpe == 1) {
        $caracteresSpeciaux = [
            '!', '@', '#', '$', '%', '^', '&', '*', '(', ')',
            '-', '_', '+', '=', '{', '}', '[', ']', ':', ';',
            '<', '>', ',', '.', '?', '/', '|', '~', '`'
        ];
    }
    return $minuscules + $majuscules + $chiffres + $caracteresSpeciaux;
}

$fullTab = creationListes($isMin, $isMaj, $isNum, $isSpe);

$listeOptions = generateSelectOptions($size);

$generer = $_POST ["generate"] ?? "pas utilisé";


$minCheck = $isMin ? "checked" : "";
$majCheck = $isMaj ? "checked" : "";
$numCheck = $isNum ? "checked" : "";
$speCheck = $isSpe ? "checked" : "";

if ($generer === "generate") {
    for ($i = 0; $i < $size; $i++) {
        $selectCaracter = array_rand($fullTab);
        $motDePasse .= $fullTab[$selectCaracter];
    }
}


$html = <<<HTML
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Générateur de Mot de Passe</title>
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: #f0f2f5;
                color: #333;
                display: flex;
                justify-content: center;
                align-items: flex-start;
                padding: 40px 20px;
                margin: 0;
            }

            .container {
                background: #ffffff;
                padding: 30px;
                border-radius: 12px;
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
                width: 100%;
                max-width: 450px;
                transition: transform 0.3s ease;
            }

            .main-title {
                text-align: center;
                color: #007bff;
                margin-bottom: 25px;
                font-size: 1.6em;
                border-bottom: 3px solid #007bff;
                padding-bottom: 10px;
            }

            .form-group {
                margin-bottom: 20px;
            }

            .form-label {
                display: block;
                margin-bottom: 8px;
                font-weight: 600;
                color: #555;
            }

            .form-select {
                width: 100%;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 6px;
                box-sizing: border-box;
                background-color: #f9f9f9;
                transition: border-color 0.3s;
            }

            .form-select:focus {
                border-color: #007bff;
                outline: none;
            }

            .checkbox-section {
                border: 1px solid #e0e0e0;
                padding: 15px;
                border-radius: 8px;
                margin-bottom: 25px;
                background-color: #f7f7f7;
            }

            .form-check {
                display: flex;
                align-items: center;
                margin-bottom: 12px;
            }

            .form-check-input {
                margin-right: 12px;
                width: 18px;
                height: 18px;
                accent-color: #28a745;
            }

            .form-check-label {
                cursor: pointer;
                user-select: none;
                font-size: 0.95em;
            }

            .btn {
                display: block;
                width: 100%;
                padding: 12px;
                border: none;
                border-radius: 6px;
                background-color: #28a745;
                color: white;
                font-size: 1.1em;
                font-weight: bold;
                cursor: pointer;
                transition: background-color 0.3s, transform 0.1s;
                text-transform: uppercase;
            }

            .btn:hover {
                background-color: #218838;
            }

            .btn:active {
                transform: translateY(1px);
            }

            .result-display {
                margin-top: 30px;
                padding: 20px;
                background-color: #e9f7ef;
                border-radius: 8px;
                text-align: center;
                border: 2px solid #28a745;
            }

            .result-title {
                font-size: 1.2em;
                color: #28a745;
                margin-top: 0;
                margin-bottom: 15px;
            }

            .password-output {
                font-size: 1.5em;
                font-weight: 700;
                word-break: break-all;
                background-color: #ffffff;
                padding: 15px;
                border-radius: 4px;
                border: 1px solid #ddd;
                color: #333;
                min-height: 25px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1 class="main-title">Générateur de Mot de Passe</h1>
            <form method="POST" action="index.php" class="password-form">
                <div class="form-group">
                    <label for="size" class="form-label">Longueur du Mot de Passe :</label>
                    <select class="form-select" aria-label="Taille du mot de passe" name="size" id="size">
                        $listeOptions
                    </select>
                </div>
                
                <div class="checkbox-section">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="1" id="use-alpha-min" name="use-alpha-min" $minCheck>
                      <label class="form-check-label" for="use-alpha-min">
                        Utiliser les lettres minuscules (a-z)
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="1" id="use-alpha-maj" name="use-alpha-maj" $majCheck>
                      <label class="form-check-label" for="use-alpha-maj">
                        Utiliser les lettres majuscules (A-Z)
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="1" id="use-num" name="use-num" $numCheck>
                      <label class="form-check-label" for="use-num">
                        Utiliser les chiffres (0-9)
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="1" id="use-symbols" name="use-symbols" $speCheck>
                      <label class="form-check-label" for="use-symbols">
                        Utiliser les symboles (!@#$%...)
                      </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <button type="submit" name="generate" value="generate" class="btn">Générer le Mot de Passe !</button>
                </div>
            </form>

            <div class="result-display">
                <h2 class="result-title">Mot de Passe Généré :</h2>
                <div class="password-output">Mot de passe :<br>$motDePasse</div>
            </div>
        </div>
    </body>
</html>
HTML;


echo $html;