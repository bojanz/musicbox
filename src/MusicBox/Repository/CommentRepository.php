<?php

namespace MusicBox\Repository;

use Doctrine\DBAL\Connection;
use MusicBox\Entity\Comment;

/**
 * Comment repository
 */
class CommentRepository implements RepositoryInterface
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
     * Saves the comment to the database.
     *
     * @param \MusicBox\Entity\Comment $comment
     */
    public function save($comment)
    {
        $commentData = array(
            'artist_id' => $comment->getArtist()->getId(),
            'user_id' => $comment->getUser()->getId(),
            'comment' => $comment->getComment(),
            'published' => $comment->getPublished(),
        );

        if ($comment->getId()) {
            $this->db->update('comments', $commentData, array('comment_id' => $comment->getId()));
        } else {
            // The comment is new, note the creation timestamp.
            $commentData['created_at'] = time();

            $this->db->insert('comments', $commentData);
            // Get the id of the newly created comment and set it on the entity.
            $id = $this->db->lastInsertId();
            $comment->setId($id);
        }
    }

    /**
     * Deletes the comment.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        return $this->db->delete('comments', array('comment_id' => $id));
    }

    /**
     * Returns the total number of comments.
     *
     * @return integer The total number of comments.
     */
    public function getCount() {
        return $this->db->fetchColumn('SELECT COUNT(comment_id) FROM comments');
    }

    /**
     * Returns a comment matching the supplied id.
     *
     * @param integer $id
     *
     * @return \MusicBox\Entity\Comment|false An entity object if found, false otherwise.
     */
    public function find($id)
    {
        $commentData = $this->db->fetchAssoc('SELECT * FROM comments WHERE comment_id = ?', array($id));
        return $commentData ? $this->buildComment($commentData) : FALSE;
    }

    /**
     * Returns a collection of comments.
     *
     * @param integer $limit
     *   The number of comments to return.
     * @param integer $offset
     *   The number of comments to skip.
     * @param array $orderBy
     *   Optionally, the order by info, in the $column => $direction format.
     *
     * @return array A collection of comments, keyed by comment id.
     */
    public function findAll($limit, $offset = 0, $orderBy = array())
    {
        return $this->getComments(array(), $limit, $offset);
    }

    /**
     * Returns a collection of published comments for the given artist id.
     *
     * @param $artistId
     *   The artist id.
     * @param integer $limit
     *   The number of comments to return.
     * @param integer $offset
     *   The number of comments to skip.
     * @param array $orderBy
     *   Optionally, the order by info, in the $column => $direction format.
     *
     * @return array A collection of comments, keyed by comment id.
     */
    public function findAllByArtist($artistId, $limit, $offset = 0, $orderBy = array())
    {
        $conditions = array(
            'artist_id' => $artistId,
            'published' => TRUE,
        );
        return $this->getComments($conditions, $limit, $offset);
    }

    /**
     * Returns a collection of comments for the given conditions.
     *
     * @param integer $limit
     *   The number of comments to return.
     * @param integer $offset
     *   The number of comments to skip.
     * @param array $orderBy
     *   Optionally, the order by info, in the $column => $direction format.
     *
     * @return array A collection of comments, keyed by comment id.
     */
    protected function getComments(array $conditions, $limit, $offset, $orderBy = array())
    {
        // Provide a default orderBy.
        if (!$orderBy) {
            $orderBy = array('created_at' => 'DESC');
        }

        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
            ->select('c.*')
            ->from('comments', 'c')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->orderBy('c.' . key($orderBy), current($orderBy));
        $parameters = array();
        foreach ($conditions as $key => $value) {
            $parameters[':' . $key] = $value;
            $where = $queryBuilder->expr()->eq('c.' . $key, ':' . $key);
            $queryBuilder->andWhere($where);
        }
        $queryBuilder->setParameters($parameters);
        $statement = $queryBuilder->execute();
        $commentsData = $statement->fetchAll();

        $comments = array();
        foreach ($commentsData as $commentData) {
            $commentId = $commentData['comment_id'];
            $comments[$commentId] = $this->buildComment($commentData);
        }
        return $comments;
    }

    /**
     * Instantiates a comment entity and sets its properties using db data.
     *
     * @param array $commentData
     *   The array of db data.
     *
     * @return \MusicBox\Entity\Comment
     */
    protected function buildComment($commentData)
    {
        // Load the related artist and user.
        $artist = $this->artistRepository->find($commentData['artist_id']);
        $user = $this->userRepository->find($commentData['user_id']);

        $comment = new Comment();
        $comment->setId($commentData['comment_id']);
        $comment->setArtist($artist);
        $comment->setUser($user);
        $comment->setComment($commentData['comment']);
        $comment->setPublished($commentData['published']);
        $createdAt = new \DateTime('@' . $commentData['created_at']);
        $comment->setCreatedAt($createdAt);
        return $comment;
    }
}
