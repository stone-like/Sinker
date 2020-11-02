<?php


namespace App\Repository;


interface CategoryRepositoryInterface
{
    public function create(string $name);

    public function findByName(string $name);

}
