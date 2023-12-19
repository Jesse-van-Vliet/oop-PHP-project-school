<?php

namespace Oopproj;

class Db
{
    public static Mysql $db;

    public function __construct()
    {
        self::$db = new Mysql();
        self::$db->connect("localhost", "project2", "root", "root");
    }
}