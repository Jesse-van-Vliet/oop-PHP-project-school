<?php

namespace Oopproj;

abstract class Account
{
    private string $name;
    private string $password;
    public static array $users = [];

    public function __construct(string $name, string $password)
    {
        $this->name = $name;
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        self::$users[] = $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }


}