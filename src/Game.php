<?php

namespace Oopproj;

class Game
{
    private Easy|Medium|Hard $wordToGuess;
    private int $attempts;
    private int $usedAttempts;

   public function __construct( Easy|Medium|Hard $wordToGuess = null, int $attempts = 6, int $usedAttempts = 0)
   {
       $this->attempts = $attempts;
       $this->usedAttempts = $usedAttempts;
       if ($wordToGuess === null) {
           $wordToGuess = self::randomWord();
       }
       $this->wordToGuess = $wordToGuess;
   }

    public static function randomWord(): word
    {
        $randomIndex = array_rand(Word::$words);
        return Word::$words[$randomIndex];
    }

    /**
     * @return Easy|Hard|Medium
     */

    public function getWordToGuess()
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




}