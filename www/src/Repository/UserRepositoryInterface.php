<?php

namespace Repository;

use Entities\User;

interface UserRepositoryInterface{
    public function checkUser(User $user);
    public function register(User $user);
    public function authenticate(User $user);
    public function findByUsername($username);
}