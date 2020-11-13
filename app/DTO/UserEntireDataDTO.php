<?php


namespace App\DTO;


class UserEntireDataDTO
{
    private $created_at;
    private $email;
    private $total_posts;
    private $total_likes;
    private $total_replies;

    /**
     * UserEntireDataDTO constructor.
     */
    public function __construct($created_at,$email,$total_posts,$total_likes,$total_replies)
    {
        $this->created_at = $created_at;
        $this->email = $email;
        $this->total_posts = $total_posts;
        $this->total_likes = $total_likes;
        $this->total_replies = $total_replies;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getTotalPosts()
    {
        return $this->total_posts;
    }

    /**
     * @return mixed
     */
    public function getTotalLikes()
    {
        return $this->total_likes;
    }

    /**
     * @return mixed
     */
    public function getTotalReplies()
    {
        return $this->total_replies;
    }




}
