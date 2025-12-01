<?php

require_once 'inc/page.inc.php';
require_once 'inc/database.inc.php';

try {
    $db = new DatabaseManager(
        dsn: 'mysql:host=mysql;dbname=lowify;charset=utf8mb4',
        username: 'lowify',
        password: 'lowifypassword'
    );
} catch (PDOException $ex) {
    echo "Erreur connexion DB : " . $ex->getMessage();
    exit;
}

$htmlContent = "";
$pageTitle = "";

$allArtists = [];
try {
    $allArtists = $db->executeQuery(<<<SQL
                                    SELECT id, name, cover
                                    FROM artist
    SQL);
} catch (PDOException $ex) {
    echo "Erreur requête : " . $ex->getMessage();
    exit;
}

$artistsAsHTML = "";

foreach ($allArtists as $artist) {
    $id = $artist['id'];
    $name = $artist['name'];
    $cover = $artist['cover'];

    $artistsAsHTML .= <<<HTML
        <a href="artist.php?id=$id" class="artist-card">
            <div class="artist-avatar">
                <img src="$cover" alt="Photo de $name" class="artist-img">
            </div>
            <div class="artist-info">
                <h4 class="artist-name">$name</h4>
                <span class="artist-label">Artiste</span>
            </div>
        </a>
HTML;
}

$pageTitle = "Artistes - Lowify";
$htmlContent = <<<HTML
    <div class="app-container">
        <div class="container">
            <nav class="nav-bar">
                <a href="index.php" class="nav-link"> < Retour à l'accueil</a>
            </nav>
        
            <h1 class="section-title">Artistes</h1>
            
            <div class="grid-layout">
                {$artistsAsHTML}
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
    }

    .container { max-width: 1400px; margin: 0 auto; padding: 0 20px; }

    .nav-bar { padding: 20px 0; margin-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .nav-link { color: #a7a7a7; text-decoration: none; font-size: 14px; }
    .nav-link:hover { color: #ffffff; }

    .section-title { font-size: 24px; font-weight: 700; margin-bottom: 30px; margin-top: 20px; }

    .grid-layout, .albums-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 24px;
    }

    .artist-card, .album-card { background: transparent; padding: 16px; border-radius: 8px; text-decoration: none;
        transition: background-color 0.3s ease; display: flex; flex-direction: column; align-items: center; text-align: center;
    }
    .artist-card:hover, .album-card:hover { background-color: #1e1e26; }

    .artist-avatar { width: 140px; height: 140px; border-radius: 50%; overflow: hidden;
        box-shadow: 0 8px 24px rgba(0,0,0,0.5); margin-bottom: 16px;
    }
    
    .album-cover-container { width: 100%; aspect-ratio: 1/1; margin-bottom: 15px; }

    .artist-img, .album-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease; }
    .album-img { border-radius: 4px; box-shadow: 0 4px 10px rgba(0,0,0,0.3); }

    .artist-card:hover .artist-img { transform: scale(1.05); }

    .artist-name, .album-name {
        color: #fff; font-size: 16px; font-weight: 700; margin: 0 0 4px 0;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%;
    }
    .artist-label, .album-year { color: #a7a7a7; font-size: 14px; }

    .artist-header { display: flex; align-items: center; gap: 30px; margin-bottom: 40px; padding-top: 20px; }
    .header-cover { width: 180px; height: 180px; border-radius: 50%; object-fit: cover; box-shadow: 0 0 20px rgba(0,0,0,0.5); }
    .header-details h1 { font-size: 48px; font-weight: 800; margin-bottom: 10px; }
    .header-details .listeners { color: #a7a7a7; font-size: 14px; margin-bottom: 15px; display: block; }
    .header-details .bio { color: #ccc; font-size: 14px; line-height: 1.5; max-width: 600px; }

    .song-list { display: flex; flex-direction: column; gap: 10px; }
    .song-row { display: flex; align-items: center; padding: 10px; border-radius: 5px; transition: background-color 0.2s; }
    .song-row:hover { background-color: rgba(255,255,255,0.1); }
    .song-cover { width: 40px; height: 40px; border-radius: 4px; margin: 0 15px; }
    .song-title { flex-grow: 1; font-weight: 600; font-size: 14px; }
    .song-duration { color: #a7a7a7; font-size: 13px; margin-right: 10px; }
CSS;

$page = new HTMLPage($pageTitle);
$page->addRawStyle($customCSS);
$page->addContent($htmlContent);
echo $page->render();