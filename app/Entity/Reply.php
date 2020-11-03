<?php


namespace App\Entity;


class Reply
{
    private $id;
    private $body;
    private $question_id;
    private $user_id;

    /**
     * Reply constructor.
     */
    public function __construct($id,$body,$question_id,$user_id)
    {
        $this->id = $id;
        $this->body = $body;
        $this->question_id = $question_id;
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
    public function getBody()
    {
        return $this->body;
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
    public function getUserId()
    {
        return $this->user_id;
    }


}
