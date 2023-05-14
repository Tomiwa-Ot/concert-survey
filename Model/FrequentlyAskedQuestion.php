<?php

namespace Project\Model;

/**
 * Represents frequently asked questions
 */
class FrequentlyAskedQuestion
{
    /** @var int $id */
    private int $id;

    /** @var string $question */
    private string $question;

    /** @var string $answer */
    private string $answer;

    public function __construct(int $id, string $question, string $answer)
    {
        $this->id = $id;
        $this->question = $question;
        $this->answer = $answer;
    }

    /**
     * Get FAQ id
     * 
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get question
     * 
     * @return string
     */
    public function getQuestion(): string
    {
        return $this->question;
    }

    /**
     * Get answer
     * 
     * @return string
     */
    public function getAnswer(): string
    {
        return $this->answer;
    }

    /**
     * Set question
     * 
     * @param string $question
     */
    public function setQuestion(string $question): void
    {
        $this->question = $question;
    }

    /**
     * Set answer
     * 
     * @param string $answer
     */
    public function setAnswer(string $answer): void
    {
        $this->answer = $answer;
    }
}