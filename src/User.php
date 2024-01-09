<?php

namespace Oopproj;

class User extends Account
{


    public function __construct(string $name, string $password, bool $admin)
    {
        parent::__construct($name, $password, $admin);
    }








}