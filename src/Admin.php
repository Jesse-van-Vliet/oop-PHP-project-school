<?php

namespace Oopproj;

class Admin extends Account
{

    public function __construct(string $name, string $password)
    {
        parent::__construct($name, $password);
    }

}