<?php

require_once 'inc/page.inc.php';

$errorMSG = $_GET["message"] ?? "Une erreur est survenue.";

$htmlContent = <<<HTML
<div class="app-container">
    <div class="container">
        <h1 class="section-title">Erreur</h1>
        <div class="error-message">
            <h2>Désolé, une erreur est survenue.</h2>
            <p>{$errorMSG}</p>
            <a href="index.php" class="back-link">Retourner à l'accueil</a>
        </div>
    </div>
</div>
HTML;


$customCSS = <<<CSS
    * { box-sizing: border-box; margin: 0; padding: 0; }
    
    .app-container {
        background-color: #121216;
        color: #ffffff;
        min-height: 100vh;
        font-family: Arial, sans-serif;
        padding-bottom: 50px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .container { max-width: 600px; margin: 0 auto; padding: 40px 20px; }

    .section-title { 
        font-size: 32px; 
        font-weight: 800; 
        color: #ff4d4d; 
        text-align: center; 
        margin-bottom: 30px; 
    }

    .error-message {
        background-color: #212121;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        border-left: 5px solid #ff4d4d;
    }

    .error-message h2 {
        font-size: 24px;
        margin-bottom: 15px;
        color: #fff;
    }

    .error-message p {
        font-size: 16px;
        margin-bottom: 25px;
        color: #ccc;
    }

    .back-link {
        display: inline-block;
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .back-link:hover {
        background-color: #0056b3;
    }
CSS;


$page = new HTMLPage(title: "Erreur - Lowify");
$page->addRawStyle($customCSS);
$page->addContent($htmlContent);
echo $page->render();