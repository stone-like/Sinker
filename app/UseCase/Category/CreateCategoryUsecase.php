<?php

namespace App\UseCase\Category;

use App\Repository\CategoryRepositoryInterface;

class CreateCategoryUsecase
{
    private $cateRepo;
    public function __construct(CategoryRepositoryInterface $cateRepo)
    {
        $this->cateRepo = $cateRepo;
    }
    public function execute(string $name)
    {
        return $this->cateRepo->create($name);
    }
}
