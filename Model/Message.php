<?php

namespace Project\Model;

/**
 * Model for messages from contact us page
 */
class Message
{
    /** @var string $subject */
    private string $subject;

    /** @var string $name */
    private string $name;

    /** @var string $email */
    private string $email;

    /** @var string $message */
    private string $message;

    /** @var bool $read */
    private bool $read;

    /** @var string $date */
    private string $date;

    public function __construct(string $subject, string $name, string $email, string $message, bool $read, string $date)
    {
        $this->subject = $subject;
        $this->name = $name;
        $this->email = $email;
        $this->message = $message;
        $this->read = $read;
        $this->date = $date;
    }

    /**
     * Update message read status
     * 
     * @param bool $status
     */
    public function setReadStatus(bool $status): void
    {
        $this->read = $status;
    }

    /**
     * Get message read status
     * 
     * @return bool
     */
    public function getReadStatus(): bool
    {
        return $this->read;
    }

    /**
     * Get message subject
     * 
     * @param int|null $limit
     * 
     * @return string
     */
    public function getSubject(?int $limit): string
    {
        if (!is_null($limit))
            return substr($this->subject, 0, $limit);

        return $this->subject;
    }

    /**
     * Get sender's name
     * 
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get sender's email
     * 
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Get message
     * 
     * @param int|null $limit
     * 
     * @return string
     */
    public function getMessage(?int $limit): string
    {
        if (!is_null($limit))
            return substr($this->message, 0, $limit);

        return $this->message;
    }

    /**
     * Get date
     * 
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }
}