<?php

namespace Oopproj;

interface Database
{
    public function connect(string $servername, string $database, string $username, string $password);
    public function insert(string $table, array $params = []);
    public function update();
    public function delete();
    public function select(array $columns, array $params = []);
}