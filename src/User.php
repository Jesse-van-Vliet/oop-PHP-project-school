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
        $table = "game";
        $params = [
            "account_id" => $this->id
        ];
        Db::$db->delete($table, $params);
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







}