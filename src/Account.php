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

    public function addGame($gameName, $score): void {
        // Voeg een nieuw spel toe aan de lijst van games
        $this->games[$gameName] = $score;
    }

    public function addWin(): void {
        // Voeg een nieuw spel toe aan de lijst van games
        $this->wonGames++;
    }

    public function addLost(): void {
        // Voeg een nieuw spel toe aan de lijst van games
        $this->lostGames++;
    }

    public function addStreak(): void {
        // Voeg een nieuw spel toe aan de lijst van games
        $this->Streak++;
    }

    public function addLongestStreak(): void {
        // Voeg een nieuw spel toe aan de lijst van games
        if ($this->Streak > $this->longestStreak) {
            $this->longestStreak = $this->Streak;
        }
    }

    public function getGameScore($gameName): string {
        // Haal de score op van een specifiek spel
        if (isset($this->games[$gameName])) {
            return $this->games[$gameName];
        } else {
            return "Spel niet gevonden";
        }
    }

    public function getWonGames(): int {
        return $this->wonGames;
    }

    public function getLongestStreak(): int {
        return $this->longestStreak;
    }

    public function clearStreak(): void {
        $this->Streak = 0;
    }


}