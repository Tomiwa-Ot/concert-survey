<?php

namespace Project\Model;

use JsonSerializable;

/**
 * Wrapper for songs
 */
class Track implements JsonSerializable
{
    /** @var string $title Song title */
    private string $title;

    /** @var string $spotifyId Song spotify id */
    private string $spotifyId;

    /** @var string $picture Song picture */
    private string $picture;

    /** @var int $duration Song duration */
    private int $duration;

    /** @var int $score Song votes */
    private int $score;

    /** @var string $color Song color */
    private string $color;
    
    public function __construct(string $title, string $spotifyId, string $picture, int $duration, int $score, string $color)
    {
        $this->title = $title;
        $this->spotifyId = $spotifyId;
        $this->picture = $picture;
        $this->duration = $duration;
        $this->score = $score;
        $this->color = $color;
    }

    /**
     * Get song title
     * 
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get song Spotify id
     * 
     * @return string
     */
    public function getSpotifyId(): string
    {
        return $this->spotifyId;
    }

    /**
     * Get song picture
     * 
     * @return string
     */
    public function getPicture(): string
    {
        return $this->picture;
    }

    /** 
     * Get song duration
     * 
     * @return string
     */
    public function getDuration(): string
    {
        $time = $this->duration;

        $uSec = $time % 1000;
        $time = floor($time / 1000);

        $seconds = $time % 60;
        $time = floor($time / 60);

        $minutes = $time % 60;
        $time = floor($time / 60);

        if ($time != 0)  {
            $hours = $time % 24;
            return $hours . ':' . $minutes . ':' . $seconds;
        }

        return $minutes . ':' . $seconds;
    }

    /**
     * Get song score
     * 
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * Set song score
     * 
     * @param int $score
     */
    public function setScore(int $score): void
    {
        $this->score = $score;
    }

    /**
     * Get song color
     * 
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'title' => $this->title,
            'spotify_id' => $this->spotifyId,
            'picture' => $this->picture,
            'duration' => $this->duration,
            'score' => $this->score,
            'color' => $this->color
        ];
    }
}

// require_once __DIR__ . '/../Library/Database.php';
// require_once __DIR__ . '/../Library/Env.php';
// use Project\Library\Env;
// use Project\Library\Database;
// Env::load(__DIR__ . '/../.env');
// // $a = new Track('Back to Back', 'https://i.scdn.co/image/ab6761610000e5eb4bd22c1711d22aa647a61097', 0, '#a12d4fc');
// $b = json_encode([['title' => 'Back to Back', 'picture' => 'https://i.scdn.co/image/ab6761610000e5eb4bd22c1711d22aa647a61097', 'score' => 0, 'color' => '#a12d4fc']]);
// $pdo = Database::getpdo();
// $image = base64_encode(file_get_contents('https://i.scdn.co/image/ab6761610000e5eb4bd22c1711d22aa647a61097'));
// $statement = $pdo->prepare("INSERT INTO back_for_everything(spotify_id, artist, picture, tracks) VALUES(?, ?, ?, ?)");
// $statement->execute(['46SHBwWsqBkxI7EeeBEQG7', 'Kodak Black', $image, $b]);