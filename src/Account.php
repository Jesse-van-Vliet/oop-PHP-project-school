<?php

namespace Oopproj;

abstract class Account
{
    private string $name;
    private string $password;
    public static array $users = [];
    private array $games = [];
    public int $wonGames = 0;
    public int $lostGames = 0;
    public int $Streak = 0;
    public int $longestStreak = 0;

    public function __construct(string $name, string $password)
    {
        $this->name = $name;
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        self::$users[] = $this;

    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }


    public function addWin(): void
    {
        // Voeg een nieuw spel toe aan de lijst van games
        $this->wonGames++;
    }

    public function addLost(): void
    {
        // Voeg een nieuw spel toe aan de lijst van games
        $this->lostGames++;
    }

    public function addStreak(): void
    {
        // Voeg een nieuw spel toe aan de lijst van games
        $this->Streak++;
    }

    public function addLongestStreak(): void
    {
        // Voeg een nieuw spel toe aan de lijst van games
        if ($this->Streak > $this->longestStreak) {
            $this->longestStreak = $this->Streak;
        }
    }


    public function getWonGames(): int
    {
        return $this->wonGames;
    }

    public function getLongestStreak(): int
    {
        return $this->longestStreak;
    }

    public function clearStreak(): void
    {
        $this->Streak = 0;
    }

    public function addGame(Game $game): void
    {
        // Voeg een nieuw spel toe aan de lijst van games
        $this->games[] = $game;
    }

    /**
     * @return int
     */
    public function getStreak(): int
    {
        return $this->Streak;
    }

    /**
     * @return int
     */
    public function getLostGames(): int
    {
        return $this->lostGames;
    }

    public static function register(string $name, string $password): void
    {
        $table = "account";
        $params = [
            "name" => $name,
            "password" => $password,
            "adminstatus_id" => 1
        ];
        Db::$db->insert($table, $params);
    }

    /**
     * @return array
     */


}