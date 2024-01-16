<?php

namespace Oopproj;

interface Database
{
    public function connect(string $servername, string $database, string $username, string $password);
    public function insert(string $table, array $params = []);
    public function update(string $table, array $params = [], array $conditions = []);
    public function delete(string $table, array $conditions = []);
    public function select(array $columns, array $params = []);
}