<?php

namespace Repository;

use Entities\Table;

interface TableRepositoryInterface{
    public function create(Table $table);
    public function read(Table $table);
    public function readLatestAddedDevices();
    public function delete(Table $table);
    public function update(Table $table);
}