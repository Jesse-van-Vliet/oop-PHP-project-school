<?php

namespace Oopproj;

use Exception;
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

    public function update()
    {
        // TODO: Implement update() method.
    }

    public function delete()
    {
        // TODO: Implement delete() method.
    }

    public function select(array $columns, array $params = [])
    {
        // TODO: Implement select() method.
        /* SELECT user.username, user.password, order.date FROM user, order
         * WHERE user.id = order.user_id
         * WHERE user.id = :id
         * WHERE user.id = 1
         * WHERE user.username LIKE '%a%'
         * WHERE order.date BETWEEN '2021-01-01' AND '2021-01-31'
         *
         * $columns = [
         *     "user" -> [
         *        "username",
         *       ],
         *     "order" -> [
         *        "date",
         *  ];
         */

        try{
            $query = "SELECT ";
            $columnNameArray = [];
            foreach($columns as $tableName => $columnArray)
            {
                if(is_array($columnArray)){
                    foreach($columnArray as $columnName){
                        // [ 0 => 'user.id', 1 => 'user.name'
                        $columnNameArray[] = "$tableName.$columnName";
                    }
                }
            }
            // SELECT user.id, user.name, order.date
            $query .= implode(", ", $columnNameArray);
            $query .= " FROM ";
            $query .= implode(", ", array_keys($columns));
            // SELECT user.id, user.name, order.date FROM user, order
            if (!empty($params))
            {
                $query .= " WHERE ";
                $conditions = [];
                foreach($params as $key => $value)
                {
                    $tableAndColumn = explode(".", $key, 2);
                    if(count($tableAndColumn) == 2)
                    {
                        // user.id
                        $table = $tableAndColumn[0];
                        $column = $tableAndColumn[1];
                    } else {
                        // id
                        $table = array_keys($columns)[0];
                        $column = $key;
                    }


//                    $columns = [
//                        "user" => [
//                            "id",
//                            "name"
//                        ]
//                    ];
//                    $params = [
//                        "id" => 5,
//                        "name" => "bla"
//                    ];
//                    var_dump(Db::$db->select($columns, $params));
//                    new DateTimeImmutable();
//                    $date = new DateTimeImmutable();
//                    $date->format("Y-m-d");

                    // $values bekijken
                    if(is_array($value))
                    {
                        // between
                        $conditions[] = "$table.column BETWEEN '".$value[0]."'AND '".$value[1]."'";
                    } elseif (strpos($key, "LIKE") !== false) {
                        // like
                        $conditions[] = "$table.$column '$value'";
                    } elseif (strpos($value, "=") !== false) {
                        // =
                        $conditions[] = "$table.$column $value";
                        // "user.id" => "= order.user_id"
                    } else {
                        $conditions[] = "$table.$column = '$value'";
                    }
                }
                // WHERE user.id = order.user_id AND user.id = 5
                $query .= implode(" AND ", $conditions);
            }
            $result = $this->connection->query($query);
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $error){
            throw new Exception($error->getMessage());
        }
    }
}