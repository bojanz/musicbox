<?php

namespace MusicBox\Repository;

use Doctrine\DBAL\Connection;
use MusicBox\Entity\Like;

/**
 * Like repository
 */
class LikeRepository implements RepositoryInterface
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $db;

    /**
     * @var \MusicBox\Repository\ArtistRepository
     */
    protected $artistRepository;

    /**
     * @var \MusicBox\Repository\UserRepository
     */
    protected $userRepository;

    public function __construct(Connection $db, $artistRepository, $userRepository)
    {
        $this->db = $db;
        $this->artistRepository = $artistRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Saves the like to the database.
     *
     * @param \MusicBox\Entity\Like $like
     */
    public function save($like)
    {
        $likeData = array(
            'artist_id' => $like->getArtist()->getId(),
            'user_id' => $like->getUser()->getId(),
        );

        if ($like->getId()) {
            $this->db->update('likes', $likeData, array('like_id' => $like->getId()));
        } else {
            // The like is new, note the creation timestamp.
            $likeData['created_at'] = time();

            $this->db->insert('likes', $likeData);
            // Get the id of the newly created like and set it on the entity.
            $id = $this->db->lastInsertId();
            $like->setId($id);
        }
    }

    /**
     * Deletes the like.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        return $this->db->delete('likes', array('like_id' => $id));
    }

    /**
     * Returns the total number of likes.
     *
     * @return integer The total number of likes.
     */
    public function getCount() {
        return $this->db->fetchColumn('SELECT COUNT(like_id) FROM likes');
    }

    /**
     * Returns a like matching the supplied id.
     *
     * @param integer $id
     *
     * @return \MusicBox\Entity\Like|false A like if found, false otherwise.
     */
    public function find($id)
    {
        $likeData = $this->db->fetchAssoc('SELECT * FROM likes WHERE like_id = ?', array($id));
        return $likeData ? $this->buildLike($likeData) : FALSE;
    }

    /**
     * Returns a collection of likes for the given user id.
     *
     * @param integer $artistId
     *   The artist id.
     * @param integer $userId
     *   The user id.
     *
     * @return \MusicBox\Entity\Like|false A like if found, false otherwise.
     */
    public function findByArtistAndUser($artistId, $userId)
    {
        $conditions = array(
            'artist_id' => $artistId,
            'user_id' => $userId,
        );
        $likes = $this->getLikes($conditions, 1, 0);
        if ($likes) {
            return reset($likes);
        }
    }

    /**
     * Returns a collection of likes.
     *
     * @param integer $limit
     *   The number of likes to return.
     * @param integer $offset
     *   The number of likes to skip.
     * @param array $orderBy
     *   Optionally, the order by info, in the $column => $direction format.
     *
     * @return array A collection of likes, keyed by like id.
     */
    public function findAll($limit, $offset = 0, $orderBy = array())
    {
        return $this->getLikes(array(), $limit, $offset, $orderBy);
    }

    /**
     * Returns a collection of likes for the given artist id.
     *
     * @param integer $artistId
     *   The artist id.
     * @param integer $limit
     *   The number of likes to return.
     * @param integer $offset
     *   The number of likes to skip.
     * @param array $orderBy
     *   Optionally, the order by info, in the $column => $direction format.
     *
     * @return array A collection of likes, keyed by like id.
     */
    public function findAllByArtist($artistId, $limit, $offset = 0, $orderBy = array())
    {
        $conditions = array(
            'artist_id' => $artistId,
        );
        return $this->getLikes($conditions, $limit, $offset, $orderBy);
    }

    /**
     * Returns a collection of likes for the given user id.
     *
     * @param $userId
     *   The user id.
     * @param integer $limit
     *   The number of likes to return.
     * @param integer $offset
     *   The number of likes to skip.
     * @param array $orderBy
     *   Optionally, the order by info, in the $column => $direction format.
     *
     * @return array A collection of likes, keyed by like id.
     */
    public function findAllByUser($userId, $limit, $offset = 0, $orderBy = array())
    {
        $conditions = array(
            'user_id' => $userId,
        );
        return $this->getLikes($conditions, $limit, $offset, $orderBy);
    }

    /**
     * Returns a collection of likes for the given conditions.
     *
     * @param integer $limit
     *   The number of likes to return.
     * @param integer $offset
     *   The number of likes to skip.
     * @param $orderBy
     *   Optionally, the order by info, in the $column => $direction format.
     *
     * @return array A collection of likes, keyed by like id.
     */
    protected function getLikes(array $conditions, $limit, $offset, $orderBy = array())
    {
        // Provide a default orderBy.
        if (!$orderBy) {
            $orderBy = array('created_at' => 'DESC');
        }

        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
            ->select('l.*')
            ->from('likes', 'l')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->orderBy('l.' . key($orderBy), current($orderBy));
        $parameters = array();
        foreach ($conditions as $key => $value) {
            $parameters[':' . $key] = $value;
            $where = $queryBuilder->expr()->eq('l.' . $key, ':' . $key);
            $queryBuilder->andWhere($where);
        }
        $queryBuilder->setParameters($parameters);
        $statement = $queryBuilder->execute();
        $likesData = $statement->fetchAll();

        $likes = array();
        foreach ($likesData as $likeData) {
            $likeId = $likeData['like_id'];
            $likes[$likeId] = $this->buildLike($likeData);
        }
        return $likes;
    }

    /**
     * Instantiates a like entity and sets its properties using db data.
     *
     * @param array $likeData
     *   The array of db data.
     *
     * @return \MusicBox\Entity\Like
     */
    protected function buildLike($likeData)
    {
        // Load the related artist and user.
        $artist = $this->artistRepository->find($likeData['artist_id']);
        $user = $this->userRepository->find($likeData['user_id']);

        $like = new Like();
        $like->setId($likeData['like_id']);
        $like->setArtist($artist);
        $like->setUser($user);
        $createdAt = new \DateTime('@' . $likeData['created_at']);
        $like->setCreatedAt($createdAt);
        return $like;
    }
}
