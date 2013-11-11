<?php

namespace MusicBox\Repository;

/**
 * Repository interface.
 *
 * "The Repository pattern just means putting a façade over your persistence
 * system so that you can shield the rest of your application code from having
 * to know how persistence works."
 *
 * A real project would use Doctrine ORM.
 */
interface RepositoryInterface
{
    /**
     * Saves the entity to the database.
     *
     * @param object $entity
     */
    public function save($entity);

    /**
     * Deletes the entity.
     *
     * @param integer $id
     */
    public function delete($id);

    /**
     * Returns the total number of entities.
     *
     * @return int The total number of entities.
     */
    public function getCount();

    /**
     * Returns an entity matching the supplied id.
     *
     * @param integer $id
     *
     * @return object|false An entity object if found, false otherwise.
     */
    public function find($id);

    /**
     * Returns a collection of entities.
     *
     * @param integer $limit
     *   The number of entities to return.
     * @param integer $offset
     *   The number of entities to skip.
     * @param array $orderBy
     *   Optionally, the order by info, in the $column => $direction format.
     *
     * @return array A collection of entity objects.
     */
    public function findAll($limit, $offset = 0, $orderBy = array());
}
