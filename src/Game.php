<?php

namespace Oopproj;

class Game
{
    private Easy|Medium|Hard $wordToGuess;
    private int $attempts;

   public function __construct( Easy|Medium|Hard $wordToGuess = null, int $attempts = 6)
   {
       $this->attempts = $attempts;
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




}