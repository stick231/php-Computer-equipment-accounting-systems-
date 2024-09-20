<?php

namespace Entities;

class User{
    private $username;
    private $password;

    public function getUsername() 
    {
        return $this->username;
    }

    public function getPassword() 
    {
        return $this->password;
    }

    public function setUsername($username)
    {
        $new = clone $this;
        $new->username = $username;
        return $new;
    }

    public function setPassword($password)
    {
        $new = clone $this;
        $password = password_hash($password, PASSWORD_DEFAULT); 
        $new->password = $password;
        return $new;
    }
}