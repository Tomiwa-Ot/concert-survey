<?php

namespace Project\Model;

/**
 * Concert class
 */
class Concert
{
    /** @var string $title Concert title */
    private string $title;

    /** @var bool $status Concert active */
    private bool $status;

    public function __construct(string $title, bool $status)
    {
        $this->title = $title;
        $this->status = $status;
    }

    /**
     * Set concert title
     * 
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Get concert title
     * 
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get concert status
     * 
     * @return bool
     */
    public function getStatus(): bool
    {
        return $this->status;
    }
}