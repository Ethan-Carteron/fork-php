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

$search = $_GET ["query"];

$htmlContent = "";
$pageTitle = "";

$searchArtists = [];
$searchAlbums = [];
$searchSongs = [];

try {
    $searchArtists = $db->executeQuery(<<<SQL
        SELECT *
        FROM artist
        WHERE (
            MATCH(name) AGAINST(:search IN NATURAL LANGUAGE MODE) OR
            name LIKE :search
        )
    SQL, $search);

    $searchAlbums = $db->executeQuery(<<<SQL
        SELECT *
        FROM album
        WHERE (
            MATCH(name) AGAINST(:search IN NATURAL LANGUAGE MODE) OR
            name LIKE :search
        )
    SQL, $search);

    $searchSongs = $db->executeQuery(<<<SQL
        SELECT *
        FROM song
        INNER JOIN album ON song.album_id = album.id
        WHERE (
            MATCH(name) AGAINST(:search IN NATURAL LANGUAGE MODE) OR
            name LIKE :search
        )
    SQL, $search);

} catch (PDOException $ex) {
    echo "Erreur requête : " . $ex->getMessage();
    exit;
}

$searchArtistsHTML = "";

foreach ($searchArtists as $artist) {
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

$searchAlbumsHTML = "";

foreach ($searchAlbums as $album) {
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

$pageTitle = "$search - Lowify";
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