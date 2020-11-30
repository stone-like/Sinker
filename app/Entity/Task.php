<?php


namespace App\Entity;


class Task
{
    private $id;
    private $bookmark_id;
    private $question_id;
    private $order;

    /**
     * Task constructor.
     */
    public function __construct($id,$bookmark_id,$question_id,$order)
    {
        $this->id = $id;
        $this->bookmark_id = $bookmark_id;
        $this->question_id = $question_id;
        $this->order = $order;
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
    public function getBookmarkId()
    {
        return $this->bookmark_id;
    }

    /**
     * @return mixed
     */
    public function getQuestionId()
    {
        return $this->question_id;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }


}
