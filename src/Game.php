<?php

namespace Oopproj;

class Game
{
    private Easy|Medium|Hard $wordToGuess;
    private int $maxAttempts;

   public function __construct( Easy|Medium|Hard $wordToGuess = null, int $maxAttempts = 6)
   {
       $this->maxAttempts = $maxAttempts;
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
    public function getMaxAttempts(): int
    {
        return $this->maxAttempts;
    }

    public function getWordsize(): int
    {
        return strlen($this->wordToGuess->getName());
    }




}