<?php


namespace App\Entity;


class Question
{
   private $id;
   private $title;
   private $slug;
   private $body;
   private $user_id;
   private $category_id;

    /**
     * Question constructor.
     * @param $id
     */
    public function __construct($id,$title,$slug,$body,$category_id,$user_id)
    {
        $this->id = $id;
        $this->title = $title;
        $this->slug = $slug;
        $this->body = $body;
        $this->category_id = $category_id;
        $this->user_id = $user_id;


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
    public function getTitle()
    {
        return $this->title;
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
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }


}
