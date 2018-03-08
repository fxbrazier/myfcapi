<?php

namespace AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Class AbstractEntity
 * @package AdminBundle\Entity
 */
abstract class AbstractEntity
{
    public static $fieldsApi = [];
    public static $fieldsApiAdmin = [];
    public static $fieldsOrder = [];
    public static $defaultFieldOrder = null;
    public static $defaultDirOrder = null;
    /**
     * Returns fields used in the API
     * @return array
     */
    public function serializeEntity(array $fields = [], $stopRecursive = false, $recursive = false)
    {
        $results   = [];
        $cc        = get_called_class();
        $fieldsApi = !empty($fields) ? $fields : $cc::$fieldsApi;
        foreach ($fieldsApi as $attribute) {
            $getter = 'get' . ucfirst($attribute);
            $value  = $this->$getter();
            if ($value instanceof AbstractEntity && !$stopRecursive) {
                $value = $value->serializeEntity([], true && !$recursive, $recursive);
            } else {
                if (($value instanceof PersistentCollection || $value instanceof ArrayCollection) && !$stopRecursive) {
                    $value = $cc::serializeEntities($value, [], true);
                }
            }
            $results[$attribute] = $value;
        }
        return $results;
    }
    /**
     * call serializeEntity on array and return array
     *
     * @param       $entities
     * @param array $fields
     * @param bool  $stopRecursive
     *
     * @param bool  $recursive
     *
     * @return array
     */
    public static function serializeEntities($entities, array $fields = [], $stopRecursive = false, $recursive = false)
    {
        $results = [];
        foreach ($entities as $entity) {
            $results[] = $entity->serializeEntity($fields, $stopRecursive, $recursive);
        }
        return $results;
    }
}