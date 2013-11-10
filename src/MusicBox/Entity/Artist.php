<?php

namespace MusicBox\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Artist
{
    /**
     * Artist id.
     *
     * @var integer
     */
    protected $id;

    /**
     * Artist name.
     *
     * @var string
     */
    protected $name;

    /**
     * Artist short biography.
     *
     * @var string
     */
    protected $shortBiography;

    /**
     * Artist biography.
     *
     * @var string
     */
    protected $biography;

    /**
     * The SoundCloud url passed to the oEmbed widget.
     *
     * @var integer
     */
    protected $soundCloudUrl;

    /**
     * Number of likes an artist has received.
     *
     * @var integer
     */
    protected $likes;

    /**
     * The filename of the main artist image.
     *
     * @var string
     */
    protected $image;

    /**
     * When the artist entity was created.
     *
     * @var DateTime
     */
    protected $createdAt;

    /**
     * The temporary uploaded file.
     *
     * $this->image stores the filename after the file gets moved to "images/".
     *
     * @var \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    protected $file;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getShortBiography()
    {
        return $this->shortBiography;
    }

    public function setShortBiography($shortBiography)
    {
        $this->shortBiography = $shortBiography;
    }

    public function getBiography()
    {
        return $this->biography;
    }

    public function setBiography($biography)
    {
        $this->biography = $biography;
    }

    public function getSoundCloudUrl()
    {
        return $this->soundCloudUrl;
    }

    public function setSoundCloudUrl($soundCloudUrl)
    {
        $this->soundCloudUrl = $soundCloudUrl;
    }

    public function getLikes()
    {
        return $this->likes;
    }

    public function setLikes($likes)
    {
        $this->likes = $likes;
    }

    public function getImage() {
        // Make sure the image is never empty.
        if (empty($this->image)) {
            $this->image = 'placeholder.gif';
        }

        return $this->image;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getFile() {
        return $this->file;
    }

    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }
}
