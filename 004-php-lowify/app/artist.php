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

$artistId = $_GET['id'] ?? 0;
$htmlContent = "";
$pageTitle = "";

if ($artistId == 0) {
    header('Location: artists.php');
    exit;
}

$artistInfos = [];
$topSongArtist = [];
$allAlbumsArtist = [];

try {
    $artistInfos = $db->executeQuery(<<<SQL
        SELECT *
        FROM artist
        WHERE id = $artistId
    SQL);

    if (empty($artistInfos)) {
        header('Location: error.php?message=Artiste introuvable');
        exit;
    }

    $topSongArtist = $db->executeQuery(<<<SQL
        SELECT song.*, album.cover
        FROM song
        INNER JOIN album ON song.album_id = album.id
        WHERE song.artist_id = $artistId
        ORDER BY song.note DESC
        LIMIT 5
    SQL);

    $allAlbumsArtist = $db->executeQuery(<<<SQL
        SELECT *
        FROM album
        WHERE album.artist_id = $artistId
        ORDER BY release_date DESC
    SQL);

} catch (PDOException $ex) {
    echo "Erreur requête : " . $ex->getMessage();
    exit;
}

$artist = $artistInfos[0];
$artistName = $artist['name'];
$artistBio = $artist['biography'];
$artistCover = $artist['cover'];

$artistListeners = "";
if ($artist['monthly_listeners'] > 1000) {
    if ($artist['monthly_listeners'] > 1000000) {
        $formatListeners = (int)($artist['monthly_listeners'] / 1000000);
        $artistListeners .= $formatListeners . "m";
    } else {
        $formatListeners = (int)($artist['monthly_listeners'] / 1000);
        $artistListeners .= $formatListeners . "k";
    }
} else {
    $artistListeners = $artist['monthly_listeners'];
}

$songsAsHTML = "";

foreach ($topSongArtist as $song) {
    $name = $song['name'];
    $cover = $song['cover'];
    $minutes = (int)($song['duration'] / 60);
    $seconds = $song['duration'] % 60;
    $note = $song['note'];
    if ($seconds < 10) {
        $seconds = "0$seconds";
    }

    $songsAsHTML .= <<<HTML
        <div class="song-row">
            <img src="$cover" class="song-cover" alt="Cover">
            <span class="song-title">$name</span>
            <span class="song-note">$note/5</span>
            <span class="song-duration">$minutes:$seconds</span>
        </div>
    HTML;
}

$albumsAsHTML = "";

foreach ($allAlbumsArtist as $album) {
    $id = $album['id'];
    $name = $album['name'];
    $cover = $album['cover'];
    $year = $album['release_date'];

    $albumsAsHTML .= <<<HTML
    <a href="album.php?id=$id" class="album-card">
        <div class="album-cover-container">
            <img src="$cover" alt="Cover de $name" class="album-img">
        </div>
        <div class="album-info">
            <h4 class="album-name">$name</h4>
            <span class="album-year">$year</span>
        </div>
    </a>
HTML;
}

$pageTitle = "$artistName - Lowify";
$htmlContent = <<<HTML
    <div class="app-container">
        <div class="container">
            <nav class="nav-bar">
                <a href="artists.php" class="nav-link">< Retour aux artistes</a>
            </nav>
    
            <div class="artist-header">
                <img src="$artistCover" alt="$artistName" class="header-cover">
                <div class="header-details">
                    <h1>$artistName</h1>
                    <span class="listeners">$artistListeners auditeurs par mois</span>
                    <p class="bio">$artistBio</p>
                </div>
            </div>
    
            <h2 class="section-title">Populaires</h2>
            <div class="song-list">
                {$songsAsHTML}
            </div>
    
            <h2 class="section-title">Discographie</h2>
            <div class="albums-grid">
                {$albumsAsHTML}
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