<?php

namespace MusicBox\Entity;

class Comment
{
    /**
     * Comment id.
     *
     * @var integer
     */
    protected $id;

    /**
     * Artist.
     *
     * @var \MusicBox\Entity\Artist
     */
    protected $artist;

    /**
     * User.
     *
     *  @var \MusicBox\Entity\User
     */
    protected $user;

    /**
     * Comment.
     *
     * @var string
     */
    protected $comment;

    /**
     * Published.
     *
     * @var boolean
     */
    protected $published;

    /**
     * When the comment entity was created.
     *
     * @var DateTime
     */
    protected $createdAt;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getArtist()
    {
        return $this->artist;
    }

    public function setArtist($artist)
    {
        $this->artist = $artist;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    public function getPublished()
    {
        return $this->published;
    }

    public function setPublished($published)
    {
        $this->published = $published;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }
}
