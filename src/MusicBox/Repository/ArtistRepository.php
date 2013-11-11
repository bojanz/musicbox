<?php

namespace MusicBox\Repository;

use Doctrine\DBAL\Connection;
use MusicBox\Entity\Artist;

/**
 * Artist repository
 */
class ArtistRepository implements RepositoryInterface
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * Saves the artist to the database.
     *
     * @param \MusicBox\Entity\Artist $artist
     */
    public function save($artist)
    {
        $artistData = array(
            'name' => $artist->getName(),
            'short_biography' => $artist->getShortBiography(),
            'biography' => $artist->getBiography(),
            'soundcloud_url' => $artist->getSoundCloudUrl(),
            'image' => $artist->getImage(),
        );

        if ($artist->getId()) {
            // If a new image was uploaded, make sure the filename gets set.
            $newFile = $this->handleFileUpload($artist);
            if ($newFile) {
                $artistData['image'] = $artist->getImage();
            }

            $this->db->update('artists', $artistData, array('artist_id' => $artist->getId()));
        }
        else {
            // The artist is new, note the creation timestamp.
            $artistData['created_at'] = time();

            $this->db->insert('artists', $artistData);
            // Get the id of the newly created artist and set it on the entity.
            $id = $this->db->lastInsertId();
            $artist->setId($id);

            // If a new image was uploaded, update the artist with the new
            // filename.
            $newFile = $this->handleFileUpload($artist);
            if ($newFile) {
                $newData = array('image' => $artist->getImage());
                $this->db->update('artists', $newData, array('artist_id' => $id));
            }
        }
    }

    /**
     * Handles the upload of an artist image.
     *
     * @param \MusicBox\Entity\Artist $artist
     *
     * @param boolean TRUE if a new artist image was uploaded, FALSE otherwise.
     */
    protected function handleFileUpload($artist) {
        // If a temporary file is present, move it to the correct directory
        // and set the filename on the artist.
        $file = $artist->getFile();
        if ($file) {
            $newFilename = $artist->getId() . '.' . $file->guessExtension();
            $file->move(MUSICBOX_PUBLIC_ROOT . '/img/artists', $newFilename);
            $artist->setFile(null);
            $artist->setImage($newFilename);
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Deletes the artist.
     *
     * @param \MusicBox\Entity\Artist $artist
     */
    public function delete($artist)
    {
        // If the artist had an image, delete it.
        $image = $artist->getImage();
        if ($image) {
            unlink('images/artists/' . $image);
        }
        return $this->db->delete('artists', array('artist_id' => $artist->getId()));
    }

    /**
     * Returns the total number of artists.
     *
     * @return integer The total number of artists.
     */
    public function getCount() {
        return $this->db->fetchColumn('SELECT COUNT(artist_id) FROM artists');
    }

    /**
     * Returns an artist matching the supplied id.
     *
     * @param integer $id
     *
     * @return \MusicBox\Entity\Artist|false An entity object if found, false otherwise.
     */
    public function find($id)
    {
        $artistData = $this->db->fetchAssoc('SELECT * FROM artists WHERE artist_id = ?', array($id));
        return $artistData ? $this->buildArtist($artistData) : FALSE;
    }

    /**
     * Returns a collection of artists, sorted by name.
     *
     * @param integer $limit
     *   The number of artists to return.
     * @param integer $offset
     *   The number of artists to skip.
     * @param array $orderBy
     *   Optionally, the order by info, in the $column => $direction format.
     *
     * @return array A collection of artists, keyed by artist id.
     */
    public function findAll($limit, $offset = 0, $orderBy = array())
    {
        // Provide a default orderBy.
        if (!$orderBy) {
            $orderBy = array('name' => 'ASC');
        }

        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
            ->select('a.*')
            ->from('artists', 'a')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->orderBy('a.' . key($orderBy), current($orderBy));
        $statement = $queryBuilder->execute();
        $artistsData = $statement->fetchAll();

        $artists = array();
        foreach ($artistsData as $artistData) {
            $artistId = $artistData['artist_id'];
            $artists[$artistId] = $this->buildArtist($artistData);
        }
        return $artists;
    }

    /**
     * Instantiates an artist entity and sets its properties using db data.
     *
     * @param array $artistData
     *   The array of db data.
     *
     * @return \MusicBox\Entity\Artist
     */
    protected function buildArtist($artistData)
    {
        $artist = new Artist();
        $artist->setId($artistData['artist_id']);
        $artist->setName($artistData['name']);
        $artist->setShortBiography($artistData['short_biography']);
        $artist->setBiography($artistData['biography']);
        $artist->setSoundCloudUrl($artistData['soundcloud_url']);
        $artist->setImage($artistData['image']);
        $artist->setLikes($artistData['likes']);
        $createdAt = new \DateTime('@' . $artistData['created_at']);
        $artist->setCreatedAt($createdAt);
        return $artist;
    }
}
