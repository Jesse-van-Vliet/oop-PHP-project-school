<?php

namespace Oopproj;

class User extends Account
{


    public function __construct( int $id, string $name, string $password, bool $admin)
    {
        parent::__construct($id, $name, $password, $admin);
    }


    public function deleteAccount(): void
    {
        $table = "account";
        $params = [
            "id" => $this->id
        ];
        Db::$db->delete($table, $params);
    }

    public function deleteUserGames(): void
    {
        $columns = [
            "game" => [
                "id"
            ]
        ];
        $params = [
            "account_id" => $this->id
        ];

        $games = Db::$db->select($columns, $params);

        foreach ($games as $game) {
            $table = "game";
            $params = [
                "id" => $game["id"]
            ];
            Db::$db->delete($table, $params);
        }
    }

    public function changeName($name){
        $table = "account";
        $params = [
            "name" => $name
        ];
    

        $conditions = [
            "id" => $this->id
        ];
        Db::$db->update($table, $params, $conditions);
    }
    
    public function deleteUserGuesses(): void
    {
        $columns = [
            "game" => [
                "id"
            ]
        ];
        $params = [
            "account_id" => $this->id
        ];
    
        $games = Db::$db->select($columns, $params);

        foreach ($games as $game) {
            $table = "guesses";
            $params = [
                "game_id" => $game["id"]
            ];

            $guesses = Db::$db->select(["guesses" => ["id"]], $params);

            foreach ($guesses as $guess) {
                $guessTable = "guesses";
                $guessParams = [
                    "id" => $guess["id"]
                ];
                Db::$db->delete($guessTable, $guessParams);
            }
        }
    }

    public function checkGamesPlayed(): int
    {
        $columns = [
            "game" => [
                "id"
            ]
        ];
        $params = [
            "account_id" => $this->id
        ];
        $games = Db::$db->select($columns, $params);
        return count($games);
    }


}