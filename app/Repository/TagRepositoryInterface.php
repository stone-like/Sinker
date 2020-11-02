<?php


namespace App\Repository;


interface TagRepositoryInterface
{
    public function create(string $name);
    public function findById(int $id);
    public function findByName(string $name);

}
