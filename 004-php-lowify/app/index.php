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

$topArtists = [];
$newAlbum = [];
$topAlbum = [];

try {
    $topArtists = $db->executeQuery(<<<SQL
        SELECT *
        FROM artist
        ORDER BY artist.monthly_listeners DESC
        LIMIT 5
    SQL);

    $newAlbum = $db->executeQuery(<<<SQL
        SELECT *
        FROM album
        ORDER BY release_date DESC
        LIMIT 5
    SQL);

    $topAlbum = $db->executeQuery(<<<SQL
        SELECT album.*, AVG(song.note)
        FROM album
        INNER JOIN song ON album.id = song.album_id
        Group BY album.id, album.name
        ORDER BY AVG(song.note) DESC
        LIMIT 5
    SQL);

} catch (PDOException $ex) {
    echo "Erreur requête : " . $ex->getMessage();
    exit;
}

$topArtistsHTML = "";

foreach ($topArtists as $artist) {
    $listeners = $artist['monthly_listeners'];
    $id = $artist['id'];
    $name = $artist['name'];
    $cover = $artist['cover'];

    $topArtistsHTML .= <<<HTML
        <a href="artist.php?id=$id" class="artist-card">
            <div class="artist-avatar">
                <img src="$cover" alt="Photo de $name" class="artist-img">
            </div>
            <div class="artist-info">
                <h4 class="artist-name">$name</h4>
                <span class="artist-label">$listeners auditeurs par mois</span>
            </div>
        </a>
HTML;
}

$newAlbumHTML = "";

foreach ($newAlbum as $album) {
    $releaseDate = $album['release_date'];
    $id = $album['id'];
    $name = $album['name'];
    $cover = $album['cover'];

    $newAlbumHTML .= <<<HTML
        <a href="album.php?id=$id" class="album-card">
            <div class="album-cover-container">
                <img src="$cover" alt="Cover de $name" class="album-img">
            </div>
            <div class="album-info">
                <h4 class="album-name">$name</h4>
                <span class="album-year">$releaseDate</span>
            </div>
        </a>
HTML;
}

$topAlbumHTML = "";

foreach ($topAlbum as $album) {
    $releaseDate = $album['release_date'];
    $id = $album['id'];
    $name = $album['name'];
    $cover = $album['cover'];

    $topAlbumHTML .= <<<HTML
        <a href="album.php?id=$id" class="album-card">
            <div class="album-cover-container">
                <img src="$cover" alt="Cover de $name" class="album-img">
            </div>
            <div class="album-info">
                <h4 class="album-name">$name</h4>
                <span class="album-year">$releaseDate</span>
            </div>
        </a>
HTML;
}

$pageTitle = "Accueil - Lowify";

$htmlContent = <<<HTML
    <div class="app-container">
        <div class="container">
            <nav class="nav-bar">
                <a href="artists.php" class="nav-link"> < Retour aux Artistes</a>
                <div class="search-bar-container">
                    <form action="search.php" method="GET" class="search-form">
                        <input 
                            type="text" 
                            name="query" 
                            placeholder="Rechercher un artiste, un album, un titre..." 
                            class="search-input"
                            required
                        >
                        <button type="submit" class="search-button">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </button>
                    </form>
                </div>
            </nav>
            
            <h1 class="section-title">Top trending</h1>
            
            <div class="grid-layout">
                {$topArtistsHTML}
            </div>
            
            <h1 class="section-title">Top sorties</h1>
            
            <div class="grid-layout">
                {$newAlbumHTML}
            </div>
            
            <h1 class="section-title">Top albums</h1>
            
            <div class="grid-layout">
                {$topAlbumHTML}
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

    .nav-bar { display: flex; flex-direction : row; padding: 20px 0; margin-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); }
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

    .search-bar-container {
        justify-content: right;
        margin: 20px 0 40px 0;
        max-width: 600px;
    }

    .search-form {
        display: flex;
        background-color: #2a2a35;
        border-radius: 50px;
        padding: 5px;
        border: 1px solid rgba(255,255,255,0.1);
        transition: border-color 0.3s, background-color 0.3s;
    }

    .search-form:focus-within {
        border-color: var(--accent, #ef5466);
        background-color: #32323e;
    }

    .search-input {
        flex-grow: 1; /* Prend toute la place disponible */
        background: transparent;
        border: none;
        color: white;
        padding: 12px 20px;
        font-size: 16px;
        outline: none; /* Enlève le contour bleu par défaut du navigateur */
    }

    .search-input::placeholder {
        color: #a2a2ad;
    }

    /* Le bouton loupe */
    .search-button {
        background: transparent;
        border: none;
        color: #a2a2ad;
        cursor: pointer;
        padding: 0 20px;
        display: flex;
        align-items: center;
        transition: color 0.3s;
    }

    .search-button:hover {
        color: white;
    }
CSS;

$page = new HTMLPage($pageTitle);
$page->addRawStyle($customCSS);
$page->addContent($htmlContent);
echo $page->render();