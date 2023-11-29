<?php

namespace Oopproj;

class Game
{
    private Easy|Medium|Hard $wordToGuess;
    private int $attempts;
    private int $usedAttempts;
    private array $guessedWords = [];
    private bool $gameWon;

   public function __construct( Easy|Medium|Hard $wordToGuess = null, int $attempts = 6, int $usedAttempts = 0, bool $gameWon = false)
   {
       $this->attempts = $attempts;
       $this->usedAttempts = $usedAttempts;
       if ($wordToGuess === null) {
           $wordToGuess = self::randomWord();
       }
       $this->wordToGuess = $wordToGuess;
       $this->gameWon = $gameWon;
   }

    public static function randomWord(): word
    {
        $randomIndex = array_rand(Word::$words);
        return Word::$words[$randomIndex];
    }

    /**
     * @return Easy|Hard|Medium
     */

    public function getWordToGuess(): Easy|Hard|Medium
    {
        return $this->wordToGuess;
    }

    /**
     * @return int
     */
    public function getAttempts(): int
    {
        return $this->attempts;
    }



    public function getWordsize(): int
    {
        return strlen($this->wordToGuess->getName());
    }

    public function setAttempts(int $attempts): int
    {
        return $this->attempts = $attempts;
    }

    /**
     * @return int
     */
    public function getUsedAttempts(): int
    {
        return $this->usedAttempts;
    }

    /**
     * @param int $usedAttempts
     */
    public function setUsedAttempts(int $usedAttempts): void
    {
        $this->usedAttempts = $usedAttempts;
    }

    public function getCompleted(): bool
    {
        return $this->completed;
    }


    public function addGuessedWord(string $guessedWord): void
    {
        $this->guessedWords[] = $guessedWord;
    }

    public function setGameWon(): void
    {
        $this->gameWon = true;
    }






}