<?php

namespace Oopproj;

use PDO;
use PDOException;

class Mysql implements Database
{

    private PDO $connection;

    public function connect(string $servername, string $database, string $username, string $password)
    {
        // TODO: Implement connect() method.
        try {
            $this->connection = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
            // set the PDO error mode to exception
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";
        } catch(PDOException $error) {
            echo "Connection failed: " . $error->getMessage();
        }
    }

    public function insert(string $table, array $params = [])
    {
        // INSERT("user", ["name" => "bla", "email" => "bla"])
        // $table = "user";
        // $params = ["name" => "bla", "email" => "bla"];
        // insert($table, $params);
        // TODO: Implement insert() method.
        // INSERT INTO user (name, email) VALUES (':name', ':email')
        // $params = ['name' => '$bla', 'email' => '$bla']
        if(!empty($params) && is_array($params)) {
            $columns = implode(", ", array_keys($params));
            $values = ":" . implode(", :", array_keys($params));
            $query = "INSERT INTO $table ($columns) VALUES ($values)";
            $insert = $this->connection->prepare($query);

            // bindValue(':name', $name);
            foreach ($params as $key => $value) {
                $insert->bindValue(":$key", $value);
            }
            echo "<pre>";
            var_dump($params);
            var_dump($insert);
            $insert->execute();
        }
    }
//    public function insert(string $table, array $params = [])
//    {
//        if (!empty($params) && is_array($params)) {
//            $columns = implode(", ", array_keys($params));
//            $values = ":" . implode(", :", array_keys($params));
//
//            $query = "INSERT INTO $table ($columns) VALUES ($values)";
//            $insert = $this->connection->prepare($query);
//
//            foreach ($params as $key => $value) {
//                $insert->bindValue(":$key", $value);
//            }
//
//            $insert->execute();
//        }
////        INSERT INTO user (name, password) VALUES (':username', ':password')";
//    }

    public function update()
    {
        // TODO: Implement update() method.
    }

    public function delete()
    {
        // TODO: Implement delete() method.
    }

    public function select()
    {
        // TODO: Implement select() method.
    }
}