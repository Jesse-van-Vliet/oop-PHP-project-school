<?php

namespace Oopproj;

class Admin extends Account
{
    private string $test;

    public function __construct(string $name, string $password, string $test)
    {
        parent::__construct($name, $password);
        $this->test = $test;
    }

    public function getTest(): string
    {
        return $this->test;
    }

}