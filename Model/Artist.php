<?php

namespace Project\Model;

/**
 * Artist class
 */
class Artist
{
    /** @var string $id Artist spotify ID */
    private string $id;

    /** @var string $name Artist name */
    private string $name;

    /** @var string $picture Artist picture */
    private string $picture;

    /** @var array<Track> $songs Artist songs */
    private array $songs = [];

    public function __construct(string $id, string $name, string $picture, ?array $songs = null)
    {
        $this->id = $id;
        $this->name = $name;

        if (filter_var($picture, FILTER_VALIDATE_URL))
            $this->picture = base64_encode(file_get_contents($picture));
        else
            $this->picture = $picture;

        if (!is_null($songs)) $this->songs = $songs;
    }

    /**
     * Get Spotify ID
     * 
     * @return
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Add song to song list
     * 
     * @param Track $track
     */
    public function addSong(Track $track): void
    {
        $foundSong = false;
        
        foreach ($this->songs as $song) {
            if ($song->getTitle() === $track->getTitle()) {
                $foundSong = true;
                break;
            }
        }

        if (!$foundSong) {
            $this->songs[] = $track;
        }
    }

    /**
     * Remove song from song list
     * 
     * @param Track $track
     */
    public function removeSong(Track $track): void
    {
        $foundSong = false;
        $index = -1;
        
        foreach ($this->songs as $key => $song) {
            if ($song->getTitle() === $track->getTitle()) {
                $foundSong = true;
                $index = $key;
                break;
            }
        }

        if ($foundSong) {
            unset($this->songs[$index]);
        }
    }

    /**
     * Get artist's name
     * 
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get artist's picture
     * 
     * @return string
     */
    public function getPicture(): string
    {
        return $this->picture;
    }

    /**
     * Get number of tracks by artist
     * 
     * @return int
     */
    public function getNumberOfTracks(): int
    {
        return count($this->songs);
    }

    /**
     * Get tracks
     * 
     * @return array<Track>
     */
    public function getTracks(): array
    {
        return $this->songs;
    }
}