<?php

namespace Oopproj;

abstract class Account
{
    protected int $id;
    private string $name;
    private string $password;
    private bool $admin;
    public static array $users = [];
    private array $games = [];
    public int $wonGames = 0;
    public int $lostGames = 0;
    public int $Streak = 0;
    public int $longestStreak = 0;

    public function __construct(int $id, string $name, string $password, bool $admin)
    {
        $this->id = $id;
        $this->name = $name;
        $this->admin = $admin;
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

    public function getAdminStatus()
    {
        if ($this->admin == false) {
            return "user";
        } else {
            return "admin";
        }

    }

    public static function register(string $name, string $password): void
    {
        $table = "account";
        $params = [
            "name" => $name,
            "password" => $password,
            "adminstatus_id" => 2
        ];
        Db::$db->insert($table, $params);
    }


    public static function nameExists(string $name): bool
    {
        $columns = [
            "account" => [
                "name"
            ]
        ];

        $params = [
            "name" => $name
        ];

        $result = Db::$db->select($columns, $params);
        if (empty($result)) {
            return false;
        } else {
            return true;
        }
    }

    public static function passwordVerify(string $name, string $password): bool|null
    {
        $columns = [
            "account" => [
                "password",
            ]
        ];

        $params = [
            "name" => $name
        ];

        $result = Db::$db->select($columns, $params);
        if (!empty($result)) {
            $dbpassword = $result[0]["password"];
            if (password_verify($password, $dbpassword)) {
                $return = true;
            } else {
                $return = false;
            }
        } else {
            $return = null;
        }

        return $return;
    }

    public static function signIn($name): User|null
    {
        $columns = [
            "account" => [
                "*",
            ]
        ];

        $params = [
            "name" => $name,
        ];

        $result = Db::$db->select($columns, $params);

        if (!empty($result)) {
            $id = $result[0]["id"];
            $name = $result[0]["name"];
            $password = $result[0]["password"];
            if ($result[0]["adminstatus_id"] == 2) {
                $admin = false;
            } else {
                $admin = true;
            }
            return new User($id, $name, $password, $admin);
        } else {
            return null;
        }
    }

    public static function getRole($name): bool|null
    {
        $columns = [
            "account" => [
                "adminstatus_id",
            ]
        ];

        $params = [
            "name" => $name,
        ];

        $result = Db::$db->select($columns, $params);

        if (!empty($result)) {
            if ($result[0]["adminstatus_id"] == 2) {
                $admin = false;
            } else {
                $admin = true;
            }
            return $admin;
        } else {
            return null;
        }
    }

    public static function getId($name): int|null
    {
        $columns = [
            "account" => [
                "id",
            ]
        ];

        $params = [
            "name" => $name,
        ];

        $result = Db::$db->select($columns, $params);

        if (!empty($result)) {
            return $result[0]["id"];
        } else {
            return null;
        }
    }

    public function getWonGames(): int
    {
        $columns = [
            "game" => [
                "status",
                "account_id"
            ]
        ];
        $params = [
            "status" => "won",
            "account_id" => $this->id
        ];
        $result = Db::$db->select($columns, $params);

        if (!empty($result)) {
            $this->wonGames = count($result);
            return count($result);
        } else {
            return 0;
        }
    }

    public function getLostGames(): int
    {
        $columns = [
            "game" => [
                "status",
                "account_id"
            ]
        ];
        $params = [
            "status" => "lost",
            "account_id" => $this->id
        ];
        $result = Db::$db->select($columns, $params);

        if (!empty($result)) {
            $this->lostGames = count($result);
            return count($result);
        } else {
            return 0;
        }
    }

    public function getCurrentStreak(): int
    {
        $columns = [
            "game" => [
                "status",
                "account_id",
                "date" // Replace with the actual name of your date column
            ]
        ];
        $params = [
            "account_id" => $this->id
        ];
        $result = Db::$db->select($columns, $params);

        $reversedResult = array_reverse($result);
        $currentStreak = 0;

        foreach ($reversedResult as $row) {
            if ($row['status'] == 'won') {
                $currentStreak++;
            } else {
                break; // Stop counting streak if a loss is encountered
            }
        }

        $this->Streak = $currentStreak;
        return $currentStreak;
    }

    public function getLongestStreak(): int
    {
        $columns = [
            "game" => [
                "status",
                "account_id",
                "date" // Replace with the actual name of your date column
            ]
        ];
        $params = [
            "account_id" => $this->id
        ];
        $result = Db::$db->select($columns, $params);

        $reversedResult = array_reverse($result);
        $currentStreak = 0;
        $longestStreak = 0;

        foreach ($reversedResult as $row) {
            if ($row['status'] == 'won') {
                $currentStreak++;
            } else {
                if ($currentStreak > $longestStreak) {
                    $longestStreak = $currentStreak;
                }
                $currentStreak = 0;
            }
        }

        // Check for the last streak in case it was the longest
        if ($currentStreak > $longestStreak) {
            $longestStreak = $currentStreak;
        }

        $this->longestStreak = $longestStreak;
        return $longestStreak;
    }


}