<?php

require_once __DIR__ . '/../Library/CSRF.php';
require_once __DIR__ . '/../Model/Artist.php';
require_once __DIR__ . '/../Model/Track.php';
require_once __DIR__ . '/../Model/Concert.php';
require_once __DIR__ . '/BaseController.php';

use Project\Library\CSRF;
use Project\Model\Artist;
use Project\Model\Concert;
use Project\Model\Track;

/**
 * Events view handler
 */
class Event extends BaseController
{
    /** @var Concert $concert */
    private Concert $concert;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Handle all GET requests
     */
    public function get(): void
    {
        if ($this->findConcert()) {
            if (isset($_GET['artist'])) {
                $artist = $this->getArtist();

                if (!is_null($artist)) {
                    render('event.php', ['title' => $this->concert->getTitle() . " | " . $artist->getName(), 'artist' => $artist]);
                    return;
                }

            } else {
                $artists = $this->getAllArtists();
                render('event.php', ['title' => $this->concert->getTitle(), 'artists' => $artists]);
                return;
            }
        }

        render('error.php', ['title' => 'Page not found', 'code' => 404, 'message' => 'Not found']);
    }

    /**
     * Handle all POST requests
     */
    public function post(): void
    {
        if ($this->findConcert() && isset($_GET['artist'])) {
            $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
            $event = str_replace("-", " ", explode('/', $uri)[2]);

            if (!empty($_POST['song'])) {
                $statement = $this->pdo->prepare("SELECT * FROM " . str_replace(" ", "_", $event) . " WHERE artist=?");
                $statement->execute([$_GET['artist']]);

                if ($statement->rowCount() > 0) {
                    $row = $statement->fetchAll(PDO::FETCH_ASSOC);
                    $tracks = [];
                    foreach (json_decode($row[0]['tracks'], true) as $track) {
                        $tracks[] = new Track($track['title'], $track['spotify_id'], $track['picture'], $track['duration'], $track['score'], $track['color']);
                    }

                    foreach ($_POST['song'] as $song) {
                        foreach ($tracks as $track) {
                            if ($track->getTitle() === $song) {
                                $track->setScore($track->getScore() + 1);
                                break;
                            }
                        }
                    }

                    $statement = $this->pdo->prepare("UPDATE " . str_replace(" ", "_", $event) . " SET tracks=? WHERE artist=?");
                    $statement->execute([json_encode($tracks, JSON_PRETTY_PRINT), $_GET['artist']]);
                }
            }

            header("Location: /event/". str_replace(" ", "-", $event));
        } else {
            $artists = $this->getAllArtists();
            render('event.php', ['title' => $this->concert->getTitle(), 'artists' => $artists]);
        }
    }

    /**
     * Return admin view
     */
    public function adminView(): void
    {
        if (!$this->isLoggedIn()) {
            header("Location: /admin/login");
            return;
        }

        $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
        $event = explode("/", $uri)[3];

        if (isset($_GET['delete'])) {
            $statement = $this->pdo->prepare("DELETE FROM " . str_replace("-", "_", $event) . " WHERE artist=?");
            $statement->execute([urldecode($_GET['delete'])]);

            header("Location: /admin/event/$event");
            return;
        }

        $statement = $this->pdo->prepare("SELECT * FROM " . str_replace("-", "_", $event));
        $statement->execute([]);
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        $artists = [];
        foreach ($rows as $row) {
            $tracks = [];
            foreach (json_decode($row['tracks'], true) as $track) {
                $tracks[] = new Track($track['title'], $track['spotify_id'], $track['picture'], $track['duration'], $track['score'], $track['color']);
            }
            $artists[] = new Artist($row['spotify_id'], $row['artist'], $row['picture'], $tracks);
        }

        render("admin/event.php", ['title' => str_replace("-", " ", $event), 'artists' => $artists]);
    }

    /**
     * Create an event
     */
    public function createEvent(): void
    {
        if (!$this->isLoggedIn()) {
            header("Location: /admin/login");
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['name']) && preg_match('/^[a-zA-Z0-9\s]+$/', $_POST['name']) === 1) {
                $statement = $this->pdo->prepare("SELECT * FROM concerts WHERE title=?");
                $statement->execute([$_POST['name']]);

                if ($statement->rowCount() === 0) {
                    $this->createConcert(new Concert($_POST['name'], 1));
                }
            }
        }

        header("Location: /admin/home");
    }

    /**
     * Modify an event
     */
    public function editEvent(): void
    {
        if (!$this->isLoggedIn()) {
            header("Location: /admin/login");
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && CSRF::isTokenValid($_POST['token'])) {
            header("Location: /admin/home");
            return;
        }
    }

    /**
     * Search for artists on Spotify
     */
    public function artistSearch(): void
    {
        $artists = $this->spotify->findArtist(urldecode($_GET['name']));
        echo json_encode($artists);
    }

    /**
     * Search for tracks on Spotify
     */
    public function trackSearch(): void
    {
        $tracks = $this->spotify->findTrack(urldecode($_GET['title']));
        echo json_encode($tracks);
    }

    /**
     * Remove track from an artist tracklist
     */
    public function removeTrack(): void
    {
        if (!$this->isLoggedIn()) {
            header("Location: /admin/login");
            return;
        }

        if (!empty($_POST['song']) && isset($_POST['artist']) && isset($_POST['event'])) {
            $statement = $this->pdo->prepare("SELECT * FROM " . str_replace(" ", "_", $_POST['event']) . " WHERE artist=?");
            $statement->execute([$_POST['artist']]);

            if ($statement->rowCount() > 0) {
                $row = $statement->fetchAll(PDO::FETCH_ASSOC);
                $tracks = json_decode($row[0]['tracks'], true);
                
                foreach ($_POST['song'] as $song) {
                    foreach ($tracks as $key => $track) {
                        if ($song === $track['title']) unset($tracks[$key]); break;
                    }
                }

                $statement = $this->pdo->prepare("UPDATE " . str_replace(" ", "_", $_POST['event']) . " SET tracks=? WHERE artist=?");
                $statement->execute([json_encode($tracks), $_POST['artist']]);
            }
        }

        header("Location: /admin/event/" . str_replace(" ", "-", $_POST['event']));
    }

    /**
     * Check if concert exists and is active
     * 
     * @return bool
     */
    private function findConcert(): bool
    {
        $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
        $concertTitle = str_replace("-", " ", explode('/', $uri)[2]);

        $statement = $this->pdo->prepare("SELECT * FROM concerts WHERE title=?");
        $statement->execute([$concertTitle]);

        if ($statement->rowCount() > 0) {
            $row = $statement->fetchAll(PDO::FETCH_ASSOC);
            if ($row[0]['title'] === $concertTitle) {
                $this->concert = new Concert($concertTitle, $row[0]['active']);
                return true;
            }
        }

        return false;
    }

    /**
     * Get artist from concert
     * 
     * @return Artist|null
     */
    private function getArtist(): Artist|null
    {
        $statement = $this->pdo->prepare("SELECT * FROM " . str_replace(' ', '_', $this->concert->getTitle()) . " WHERE artist=?");
        $statement->execute([$_GET['artist']]);

        if ($statement->rowCount() > 0) {
            $row = $statement->fetchAll(PDO::FETCH_ASSOC);
            $tracks = [];
            foreach (json_decode($row[0]['tracks'], true) as $track) {
                $tracks[] = new Track($track['title'], $track['spotify_id'], $track['picture'], $track['duration'], $track['score'], $track['color']);
            }
            $artist = new Artist($row[0]['id'], $row[0]['artist'], $row[0]['picture'], $tracks);

            return $artist;
        }

        return null;
    }

    /**
     * Get all artists for a concert
     * 
     * @return array<Artist>
     */
    private function getAllArtists(): array
    {
        $statement = $this->pdo->prepare("SELECT * FROM " . str_replace(' ', '_', $this->concert->getTitle()));
        $statement->execute([]);

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        $result = [];

        foreach ($rows as $row) {
            $tracks = [];
            foreach (json_decode($row['tracks'], true) as $track) {
                $tracks[] = new Track($track['title'], $track['spotify_id'], $track['picture'], $track['duration'], $track['score'], $track['color']);
            }

            $result[] = new Artist($row['id'], $row['artist'], $row['picture'], $tracks);
        }

        return $result;
    }

    /**
     * Create concert
     */
    private function createConcert(Concert $concert): void
    {
        $statement = $this->pdo->prepare("INSERT INTO concerts(title, active) VALUES(?, ?)");
        $statement->execute([$_POST['name'], 1]);

        $statement = $this->pdo->prepare(
            "CREATE TABLE " . str_replace(" ", "_", $concert->getTitle()) . " (
                id int(50) AUTO_INCREMENT,
                spotify_id varchar(50) NOT NULL,
                artist varchar(255) NOT NULL,
                picture longblob NOT NULL,
                tracks varchar(7000) NOT NULL,
                votes int(50) NOT NULL DEFAULT 0,
                primary key(id)
            )"
        );
        $statement->execute([]);
    }

    /**
     * Add artist to concert
     */
    public function addArtist(): void
    {
        if (!$this->isLoggedIn()) {
            header("Location: /admin/login");
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['artist']) && !is_array($_POST['artist'])) {
            $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
            $concertTitle = str_replace("-", "_", explode('/', $uri)[3]);

            $data = json_decode($_POST['artist'], true);
            $artist = new Artist($data['spotify_id'], $data['name'], $data['picture']);

            $statement = $this->pdo->prepare("SELECT * FROM ". $concertTitle . " WHERE artist=?");
            $statement->execute([$artist->getName()]);

            if ($statement->rowCount() === 0) {
                $statement = $this->pdo->prepare("INSERT INTO " . $concertTitle . "(spotify_id, artist, picture, tracks) VALUES(?, ?, ?, ?)");
                $statement->execute([$artist->getId(), $artist->getName(), $artist->getPicture(), json_encode([])]);
            }

            header("Location: /admin/event/" . str_replace("_", "-", $concertTitle));
            return;
        }

        header("Location: /admin/home");
    }

    /**
     * Add track to artist's repository of songs
     */
    public function addTrack(): void
    {
        if (!$this->isLoggedIn()) {
            header("Location: /admin/login");
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !is_array($_POST['track']) && isset($_POST['artist']) && isset($_POST['event'])) {
            $data = json_decode($_POST['track'], true);

            $statement = $this->pdo->prepare("SELECT * FROM ". $_POST['event'] . " WHERE artist=?");
            $statement->execute([$_POST['artist']]);

            if ($statement->rowCount() > 0) {
                $row = $statement->fetchAll(PDO::FETCH_ASSOC);
                $tracks = json_decode($row[0]['tracks']);
                $tracks[] = [
                    "title" => $data['name'],
                    "spotify_id" => $data['spotify_id'],
                    "picture" => $data['picture'],
                    "duration" => $data['duration'],
                    "score" => $data['score'],
                    "color" => $data['color'],
                ];

                $statement = $this->pdo->prepare("UPDATE " . $_POST['event'] . " SET tracks=? WHERE artist=?");
                $statement->execute([json_encode($tracks), $_POST['artist']]);
            }
        }

        header("Location: /admin/event/" . str_replace("_", "-", $_POST['event']));

    }

    /**
     * Remove artist from concert
     */
    public function removeArtist(): void
    {
        if (!$this->isLoggedIn()) {
            header("Location: /admin/login");
            return;
        }

        if ($this->findConcert()) {
            $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
            $concertTitle = str_replace("-", "_", explode('/', $uri)[1]);

            $statement = $this->pdo->prepare("DELETE FROM " . $concertTitle . " WHERE id=?");
            $statement->execute([$_GET['id']]);

            header("Location: " . $_SERVER['REQUEST_URI']);
            return;
        }

        header("Location: /admin/home");
    }

    /**
     * Generate a hex color
     * 
     * @return string
     */
    private function generateColor(): string
    {
        return '#' . $this->generateRandomHex() . $this->generateRandomHex() . $this->generateRandomHex();
    }

    /**
     * Generate random hex
     * 
     * @return string
     */
    private function generateRandomHex(): string
    {
        return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
    }
}