<?php


namespace App\Repository;


use App\Entity\Tag;

class TestTagRepository implements TagRepositoryInterface
{
    public static $currentId = 1;
    public static $data = [
        ["id" => 1, "name" => "dummy1"],
    ];

    public function create(string $name)
    {
        self::$currentId++;
        $tag = [
            "id" => self::$currentId,
            "name" => $name
        ];
        array_push(self::$data, $tag);
        return new Tag($tag["id"], $tag["name"]);

    }

    public function findById(int $id)
    {

        if (!$key = array_search($id, array_column(self::$data, "id"))) {
            return null;
        };


        return new Tag(self::$data[$key]["id"], self::$data[$key]["name"]);
    }

    public function tearDown()
    {
        self::$currentId = 1;
        self::$data = [
            ["id" => 1, "name" => "dummy1"],
        ];
    }
}
