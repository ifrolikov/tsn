<?php

namespace app\components\managers;

/**
 * Class PasswordManager
 * @package app\components\managers
 */
class PasswordManager
{
    /** @var string */
    private $password;

    /**
     * @return string
     */
    public function encrypt(): string
    {
        return md5(md5($this->password));
    }

    /**
     * @param string $password
     * @return PasswordManager
     */
    public function setPassword(string $password): PasswordManager
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}