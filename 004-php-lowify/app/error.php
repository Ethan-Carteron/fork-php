<?php

require_once 'inc/page.inc.php';

$errorMessage = $_GET['message'] ?? "Une erreur inconnue est survenue.";

$htmlContent = <<<HTML
    <div class="error-box">
        <h1>Oups !</h1>
        <p>{$errorMessage}</p>
        <a href="index.php" class="btn-return">Retourner à l'accueil</a>
    </div>
HTML;

$basicCSS = <<<CSS
    body {
        font-family: sans-serif;
        background-color: #1a1a1a; /* Fond sombre simple */
        color: #fff;
        display: flex;
        justify-content: center; /* Centrage horizontal */
        align-items: center;     /* Centrage vertical */
        height: 100vh;           /* Prend toute la hauteur de l'écran */
        margin: 0;
    }

    .error-box {
        background-color: #2a2a2a;
        padding: 40px;
        border-radius: 10px;
        text-align: center;
        border: 1px solid #444;
        max-width: 500px;
        width: 100%;
        box-shadow: 0 4px 10px rgba(0,0,0,0.5);
    }

    h1 {
        color: #ff5555; /* Rouge pour signaler l'erreur */
        margin-top: 0;
    }

    p {
        font-size: 18px;
        margin-bottom: 30px;
        line-height: 1.5;
    }

    .btn-return {
        display: inline-block;
        padding: 10px 20px;
        background-color: #fff;
        color: #000;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
    }

    .btn-return:hover {
        background-color: #ddd;
    }
CSS;

$page = new HTMLPage("Erreur - Lowify");
$page->addRawStyle($basicCSS);
$page->setContent($htmlContent);
echo $page->render();