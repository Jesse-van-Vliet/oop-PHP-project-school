<?php

namespace Oopproj;

abstract class Word
{

    private string $name;
    public static array $words = [];

    public function __construct(string $name)
    {
        $this->name = $name;
        self::$words[] = $this;
    }

    /**
     * @return string
     */
    public static function difference(string $word)
    {
        $columns = [
            "words" => [
                "name"
            ]
        ];
        $params = [
            "name" => $word
        ];
        $result = Db::$db->select($columns, $params);
        return $result;
    }

    public function getRandomWord(): string
    {
        $randomWord = self::$words[array_rand(self::$words)];
        return $randomWord->getName();
    }

    public static function addWord($word): void
    {
        $table = "words";
        $params = [
            "name" => $word
        ];
        Db::$db->insert($table, $params);
    }


}