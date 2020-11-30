<?php


namespace App\Entity;


class Category
{
   private $id;
   private $name;
   private $slug;


    public function __construct($id,$name,$slug)
    {
        $this->id = $id;
        $this->name = $name;
        $this->$slug = $slug;


    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }


}
