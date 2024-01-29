<?php

namespace Oopproj;

class Game
{
    private string $wordToGuess;
    private int $attempts;
    private int $usedAttempts;
    private array $guessedWords = [];
    private bool $gameWon;

    public function __construct(Easy|Medium|Hard $wordToGuess = null, int $attempts = 6, int $usedAttempts = 0, bool $gameWon = false)
    {
        $this->attempts = $attempts;
        $this->usedAttempts = $usedAttempts;
        if ($wordToGuess === null) {
            $wordToGuess = self::randomWord();
        }
        $this->wordToGuess = $wordToGuess;
        $this->gameWon = $gameWon;
    }

    public static function createGame(): void
    {
        $table = "game";
        $params = [
            "date" => date("Y-m-d H:i:s"),
            "account_id" => User::getId($_SESSION["user"]->getName()),
            "words_id" => self::randomWord(),
        ];
        Db::$db->insert($table, $params);
    }

    public static function randomWord(): int|null
    {
        $columns = [
            "words" => [
                "id",
            ]
        ];
        $result = Db::$db->select($columns);
        if (!empty($result)) {
//            echo "<pre>";
//            return $result[array_rand($result)][0];
//            die(var_dump(array_rand($result)));
            return array_rand($result);
        } else {
            return null;
        }
    }

    public static function checkCompleted()
    {
        $columns = [
            "game" => [
                "status",
                "account_id"
            ]
        ];
        $params = [
            "account_id" => User::getId($_SESSION["user"]->getName())
        ];
        $result = Db::$db->select($columns, $params);
        if (!empty($result)) {
            $lastKey = array_key_last($result);
            return $result[$lastKey]["status"];
        } else {
            return "pass";
        }
    }

    public static function getGameId(): int|null
    {
        $columns = [
            "game" => [
                "id",
            ]
        ];
        $params = [
            "account_id" => User::getId($_SESSION["user"]->getName())
        ];
        $result = Db::$db->select($columns, $params);
        if (!empty($result)) {
            $lastKey = array_key_last($result);
            return $result[$lastKey]["id"];
        } else {
            return null;
        }
    }

    public static function setGameWon(): void
    {
        $table = "game";
        $params = [
            "status" => "won",
        ];
        $conditions = [
            "id" => self::getGameId()
        ];
        Db::$db->update($table, $params, $conditions);
    }

    public static function setGameLost(): void
    {
        $table = "game";
        $params = [
            "status" => "lost",
        ];
        $conditions = [
            "id" => self::getGameId()
        ];
        Db::$db->update($table, $params, $conditions);
    }

    public static function addGuessedWord($word): void
    {
        $table = "guesses";
        $params = [
            "name" => $word,
            "game_id" => self::getGameId(),
        ];
        Db::$db->insert($table, $params);
    }

    public static function getGuessedWords(): array|null
    {
        $columns = [
            "guesses" => [
                "name",
            ]
        ];
        $params = [
            "game_id" => Game::getGameId()
        ];
        $result = Db::$db->select($columns, $params);
        if (!empty($result)) {
            return $result;
        } else {
            return null;
        }
    }

    public static function getWordToGuess($gameId): string|null
    {
        $columns = [
            "game" => [
                "words_id",
            ]
        ];
        $params = [
            "id" => $gameId
        ];
        $wordId = Db::$db->select($columns, $params);
//        die(var_dump($wordId));
        $columns = [
            "words" => [
                "name",
            ]
        ];
        $params = [
            "id" => $wordId[0]["words_id"]
        ];
        $result = Db::$db->select($columns, $params);
//        die($result[0]["name"]);
        if (!empty($result)) {
            return $result[0]["name"];
        } else {
            return null;
        }
    }

    public function setWordToGuess($wordToGuess): void
    {
        $this->wordToGuess = $wordToGuess;
    }

    /**
     * @return Easy|Hard|Medium
     */

    public function getWordToGuesss(): string
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


    public function addGuessedWords(string|array $guessedWord): void
    {
        if (is_array($guessedWord)) {
            // If $guessedWord is an array, concatenate its elements into a string
            $guessedWordString = implode(', ', $guessedWord);
            $this->guessedWords[] = $guessedWordString;
        } else {
            // If $guessedWord is a string, add it directly
            $this->guessedWords[] = $guessedWord;
        }
    }

    public function setGameWonn(): void
    {
        $this->gameWon = true;
    }


}