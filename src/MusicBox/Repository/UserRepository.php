<?php

namespace MusicBox\Repository;

use Doctrine\DBAL\Connection;
use MusicBox\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * User repository
 */
class UserRepository implements RepositoryInterface, UserProviderInterface
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $db;

    /**
     * @var \Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder
     */
    protected $encoder;

    public function __construct(Connection $db, $encoder)
    {
        $this->db = $db;
        $this->encoder = $encoder;
    }

    /**
     * Saves the user to the database.
     *
     * @param \MusicBox\Entity\User $user
     */
    public function save($user)
    {
        $userData = array(
            'username' => $user->getUsername(),
            'mail' => $user->getMail(),
            'role' => $user->getRole(),
        );
        // If the password was changed, re-encrypt it.
        if (strlen($user->getPassword()) != 88) {
            $userData['salt'] = uniqid(mt_rand());
            $userData['password'] = $this->encoder->encodePassword($user->getPassword(), $userData['salt']);
        }

        if ($user->getId()) {
            // If a new image was uploaded, make sure the filename gets set.
            $newFile = $this->handleFileUpload($user);
            if ($newFile) {
                $userData['image'] = $user->getImage();
            }

            $this->db->update('users', $userData, array('user_id' => $user->getId()));
        } else {
            // The user is new, note the creation timestamp.
            $userData['created_at'] = time();

            $this->db->insert('users', $userData);
            // Get the id of the newly created user and set it on the entity.
            $id = $this->db->lastInsertId();
            $user->setId($id);

            // If a new image was uploaded, update the user with the new
            // filename.
            $newFile = $this->handleFileUpload($user);
            if ($newFile) {
                $newData = array('image' => $user->getImage());
                $this->db->update('users', $newData, array('user_id' => $id));
            }
        }
    }

    /**
     * Handles the upload of a user image.
     *
     * @param \MusicBox\Entity\User $user
     *
     * @param boolean TRUE if a new user image was uploaded, FALSE otherwise.
     */
    protected function handleFileUpload($user) {
        // If a temporary file is present, move it to the correct directory
        // and set the filename on the user.
        $file = $user->getFile();
        if ($file) {
            $newFilename = $user->getUsername() . '.' . $file->guessExtension();
            $file->move(MUSICBOX_PUBLIC_ROOT . '/img/users', $newFilename);
            $user->setFile(null);
            $user->setImage($newFilename);
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Deletes the user.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        return $this->db->delete('users', array('user_id' => $id));
    }

    /**
     * Returns the total number of users.
     *
     * @return integer The total number of users.
     */
    public function getCount() {
        return $this->db->fetchColumn('SELECT COUNT(user_id) FROM users');
    }

    /**
     * Returns a user matching the supplied id.
     *
     * @param integer $id
     *
     * @return \MusicBox\Entity\User|false An entity object if found, false otherwise.
     */
    public function find($id)
    {
        $userData = $this->db->fetchAssoc('SELECT * FROM users WHERE user_id = ?', array($id));
        return $userData ? $this->buildUser($userData) : FALSE;
    }

    /**
     * Returns a collection of users.
     *
     * @param integer $limit
     *   The number of users to return.
     * @param integer $offset
     *   The number of users to skip.
     * @param array $orderBy
     *   Optionally, the order by info, in the $column => $direction format.
     *
     * @return array A collection of users, keyed by user id.
     */
    public function findAll($limit, $offset = 0, $orderBy = array())
    {
        // Provide a default orderBy.
        if (!$orderBy) {
            $orderBy = array('username' => 'ASC');
        }

        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
            ->select('u.*')
            ->from('users', 'u')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->orderBy('u.' . key($orderBy), current($orderBy));
        $statement = $queryBuilder->execute();
        $usersData = $statement->fetchAll();

        $users = array();
        foreach ($usersData as $userData) {
            $userId = $userData['user_id'];
            $users[$userId] = $this->buildUser($userData);
        }

        return $users;
    }

    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username)
    {
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
            ->select('u.*')
            ->from('users', 'u')
            ->where('u.username = :username OR u.mail = :mail')
            ->setParameter('username', $username)
            ->setParameter('mail', $username)
            ->setMaxResults(1);
        $statement = $queryBuilder->execute();
        $usersData = $statement->fetchAll();
        if (empty($usersData)) {
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
        }

        $user = $this->buildUser($usersData[0]);
        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
        }

        $id = $user->getId();
        $refreshedUser = $this->find($id);
        if (false === $refreshedUser) {
            throw new UsernameNotFoundException(sprintf('User with id %s not found', json_encode($id)));
        }

        return $refreshedUser;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return 'MusicBox\Entity\User' === $class;
    }

    /**
     * Instantiates a user entity and sets its properties using db data.
     *
     * @param array $userData
     *   The array of db data.
     *
     * @return \MusicBox\Entity\User
     */
    protected function buildUser($userData)
    {
        $user = new User();
        $user->setId($userData['user_id']);
        $user->setUsername($userData['username']);
        $user->setSalt($userData['salt']);
        $user->setPassword($userData['password']);
        $user->setMail($userData['mail']);
        $user->setRole($userData['role']);
        $createdAt = new \DateTime('@' . $userData['created_at']);
        $user->setCreatedAt($createdAt);
        return $user;
    }
}
