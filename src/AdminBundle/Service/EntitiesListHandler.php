<?php

namespace AdminBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use AdminBundle\Controller\JsonController;
use AdminBundle\Entity\AbstractEntity;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class EntitiesListHandler
 *
 * @package AdminBundle\Service
 */
class EntitiesListHandler
{
    protected $em;
    protected $entitiesOrder;
    protected $paginator;
    protected $jsonController;
    protected $requestStack;
    protected $orders;
    protected $count;
    protected $entities;
    /**
     * EntitiesListHandler constructor.
     *
     * @param \Doctrine\ORM\EntityManager                       $entityManager
     * @param \AdminBundle\Service\EntitiesOrder     $entitiesOrder
     * @param \AdminBundle\Service\Paginator         $paginator
     * @param \AdminBundle\Controller\JsonController $jsonController
     */
    public function __construct(
        EntityManager $entityManager,
        EntitiesOrder $entitiesOrder,
        Paginator $paginator,
        JsonController $jsonController,
        RequestStack $requestStack
    ) {
        $this->em             = $entityManager;
        $this->entitiesOrder  = $entitiesOrder;
        $this->paginator      = $paginator;
        $this->jsonController = $jsonController;
        $this->requestStack   = $requestStack;
    }

    /**
     * @param       $className
     * @param array $criteria
     *
     * @return $this
     */
    public function handleList($className, $fieldsSearch = [], $criteria = [], $qb = null)
    {

        $json       = $this->jsonController->getJson($this->requestStack->getCurrentRequest());
        $repository = $this->em->getRepository($className);
        $ids        = $json->get('ids');

        if (isset($ids)) {
            $criteria['id'] = ['IN' => $ids];
        }

        $ordersRequest = empty($json['orders']) ? [] : $json['orders'];

        $this->orders = $this->entitiesOrder->handleOrders(
            $ordersRequest,
            $className::$fieldsOrder,
            $className::$defaultFieldOrder,
            $className::$defaultDirOrder
        )->getOrders();

        //dump($ordersRequest,$this->orders);die;


        $this->count = $repository->countByAdd($json['search'], $fieldsSearch, $criteria);
        if (empty($json['perPage'])) {
            $paginator = $this->paginator->handlePagination($this->count);
        } else {
            $paginator = $this->paginator->handlePagination(
                $this->count,
                $json['page'],
                ($json['perPage'] != -1 ? $json['perPage'] : $this->count)
            );
        }
        $this->entities = $repository->getByAdd(
            $json['search'],
            $fieldsSearch,
            $this->orders,
            $criteria,
            $paginator->getLimit(),
            $paginator->getOffset(),
            $qb
        );

        return $this;
    }

    /**
     * add fields which is in joins attributes (ex : user.name)
     *
     * @param $className
     * @param $fieldsSearch
     */
    private function addFieldsJoinSearch($className, &$fieldsSearch)
    {
        $associationMappings = $this->em->getClassMetadata($className)->getAssociationMappings();
        foreach ($associationMappings as $associationName => $mapping) {
            if ($mapping['type'] === ClassMetadataInfo::ONE_TO_ONE
                ||
                $mapping['type'] === ClassMetadataInfo::MANY_TO_ONE
            ) {
                $associationFields = $this->em->getClassMetadata($mapping['targetEntity'])->getFieldNames();
                foreach ($associationFields as $associationField) {
                    $fieldSearch    = $associationName . "." . $associationField;
                    $fieldsSearch[] = $fieldSearch;
                }
            }
        }
    }

    /**
     * return entities
     * @return mixed
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * serialized entities
     *
     * @param array $fields
     *
     * @return array
     */
    public function serializeEntities($fields = [], $recursive = false)
    {
        return AbstractEntity::serializeEntities($this->getEntities(), $fields, false, $recursive);
    }

    /**
     * @param array $fields
     *
     * @return array
     */
    public function getResults($fields = [], $recursive = false)
    {
        $results               = [];
        $results["items"]      = $this->serializeEntities($fields, $recursive);
        $results["itemsCount"] = $this->paginator->getItemsCount();
        $results["orders"]     = $this->orders;
        return $results;
    }

    /**
     * @return mixed
     */
    public function getOrders()
    {
        return $this->orders;
    }

    public function getCount()
    {
        return $this->count;
    }

    public function getPaginator()
    {
        return $this->paginator;
    }
}