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

