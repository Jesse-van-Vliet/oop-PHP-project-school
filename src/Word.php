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
    public function getName(): string
    {
        return $this->name;
    }


}