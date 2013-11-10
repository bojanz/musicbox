<?php

namespace MusicBox\Entity;

class Like
{
    /**
     * Like id.
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
     * When the like entity was created.
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

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }
}
