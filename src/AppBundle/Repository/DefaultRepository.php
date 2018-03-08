<?php

namespace AppBundle\Repository;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Query;
use AdminBundle\Helper\ORM\QueryBuilderHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use AdminBundle\Helper\Response\ApiResponse;
use DoctrineExtensions\Query\Mysql\StrToDate;
/**
 * Default repository
 */
class DefaultRepository extends EntityRepository
{
    private $joins = 0;
    private $joinsPreparation = [];
    private $aliases = [];
    /**
     * Count entities for criteria
     *
     * @param array $criteria
     *
     * @return int
     */
    public function countBy(array $criteria = [])
    {
        $documentsQb = $this->createQueryBuilder('q');
        $documentsQb->select('count(q.id)');
        $this->setFilters($documentsQb, $criteria);
        $query  = $documentsQb->getQuery();
        $result = $query->getResult();
        $this->resetQueryBuilder();
        if (empty($result)) {
            return 0;
        } else {
            return (int) $result[count($result) - 1][1];
        }
    }
    /**
     * @param       $search
     * @param       $fieldsSearch
     * @param array $criteria
     *
     * @return int
     */
    public function countByAdd($search, $fieldsSearch, $criteria = [])
    {
        $documentsQb = $this->createQueryBuilder('q');
        $documentsQb->select('count(distinct(q.id))');
        $this->setFilters($documentsQb, $criteria);
        $this->applyJoinSearch($documentsQb, false);
        $this->applyCriteriaSearch($documentsQb, $search, $fieldsSearch);
        $query = $documentsQb->getQuery();
        $result = $query->getResult();
        $this->resetQueryBuilder();
        if (empty($result)) {
            return 0;
        } else {
            return (int) $result[count($result) - 1][1];
        }
    }
    /**
     * @param $documentsQb
     * @param $search
     * @param $fieldsSearch
     */
    private function applyCriteriaSearch(&$documentsQb, $search, $fieldsSearch)
    {
        $searchDate = false;
        if ($search !== null && $search !== '' && !empty($fieldsSearch)) {
            $orModule = $documentsQb->expr()->orx();
            foreach ($fieldsSearch as $field) {
                if (strpos($field, ".")) {
                    $explode = explode(".", $field);
                    $orModule->add(
                        $documentsQb->expr()
                            ->like($this->aliases[$explode[0]] . '.' . $explode[1], ':search')
                    );
                } else {
                    $type = $this->getClassMetadata()->getTypeOfField($field);
                    if ($type == 'datetime') {
                        $dateAndModule = $documentsQb->expr()->andX();
                        $functions = [
                            'YEAR',
                            'MONTH',
                            'DAY',
                        ];
                        foreach ($functions as $function) {
                            $dateAndModule->add(
                                $documentsQb
                                    ->expr()
                                    ->like(
                                        $function . '(q.' . $field . ")",
                                        $function . "(STR_TO_DATE(:searchDate, '%d/%m/%Y'))"
                                    )
                            );
                        }
                        $orModule->add($dateAndModule);
                        $searchDate = true;
                    } else {
                        $orModule->add($documentsQb->expr()->like('q.' . $field, ':search'));
                    }
                }
            }
            $documentsQb->andWhere($orModule);
            $documentsQb->setParameter('search', '%' . $search . '%', \PDO::PARAM_STR);
            if ($searchDate) {
                $documentsQb->setParameter('searchDate', $search, \PDO::PARAM_STR);
            }
        }
    }
    /**
     * Set query filters
     *
     * @param $documentsQb
     * @param $criteria
     *
     * @return mixed
     */
    private function setFilters(&$documentsQb, $criteria)
    {
        foreach ($criteria as $field => $val) {
            ++$this->joins;
            if ($val === null || $val === '') {
                continue;
            }
            QueryBuilderHelper::applyCriteria($documentsQb, 'q', $field, $val, $this->joins, $this->joinsPreparation);
        }
        QueryBuilderHelper::applyJoinCriterias($documentsQb, $this->joinsPreparation);
        return $documentsQb;
    }
    /**
     * Get entity by criteria
     *
     * @param array $criteria
     * @param array $orders
     * @param int   $limit
     * @param int   $offset
     *
     * @return array
     */
    public function getOneBy(
        array $criteria = [],
        array $orders = [],
        $limit = -1,
        $offset = -1
    ) {
        $result = $this->getBy($criteria, $orders, $limit, $offset);
        if ($result->isEmpty()) {
            return null;
        } else {
            return $result->first();
        }
    }
    /**
     * @param      $documentsQb
     * @param bool $addSelect
     */
    public function applyJoinSearch(&$documentsQb, $addSelect = true)
    {
        $associationMappings = $this->getClassMetadata()->getAssociationMappings();
        $count = 0;
        foreach ($associationMappings as $association => $mapping) {
            if ($mapping['type'] === ClassMetadataInfo::MANY_TO_ONE
                ||
                $mapping['type'] === ClassMetadataInfo::ONE_TO_ONE
            ) {
                $alias                       = "join_" . $count;
                $this->aliases[$association] = $alias;
                $documentsQb->leftJoin('q.' . $association, $alias);
                if ($addSelect) {
                    $documentsQb->addSelect($alias);
                }
                $count++;
            }
        }
    }
    /**
     * @param       $search
     * @param       $fieldsSearch
     * @param       $orders
     * @param array $criteria
     * @param int   $limit
     * @param int   $offset
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getByAdd(
        $search,
        $fieldsSearch,
        $orders,
        $criteria = [],
        $limit = -1,
        $offset = -1,
        $qb = null
    ) {
        $documentsQb = isset($qb) ? $qb : $this->createQueryBuilder('q');
        $this->setOrder($documentsQb, $orders);
        $this->setFilters($documentsQb, $criteria);
        $this->applyJoinSearch($documentsQb);
        $this->applyCriteriaSearch($documentsQb, $search, $fieldsSearch);
        if ($limit != -1) {
            $documentsQb->setFirstResult($offset);
        }
        if ($offset != -1) {
            $documentsQb->setMaxResults($limit);
        }
        $query = $documentsQb->getQuery();
        $this->resetQueryBuilder();
        return new ArrayCollection($query->getResult());
    }
    /**
     * Get entities collection by criteria
     *
     * @param array $criteria
     * @param array $orders
     * @param int   $limit
     * @param int   $offset
     *
     * @return ArrayCollection
     */
    public function getBy(
        array $criteria = [],
        array $orders = [],
        $limit = -1,
        $offset = -1
    ) {
        $documentsQb = $this->createQueryBuilder('q');
        $documentsQb->select('q');
        $this->setFilters($documentsQb, $criteria);
        $this->setOrder($documentsQb, $orders);
        if ($offset != -1) {
            $documentsQb->setFirstResult($offset);
        }
        if ($limit != -1) {
            $documentsQb->setMaxResults($limit);
        }
        $query = $documentsQb->getQuery();
        $this->resetQueryBuilder();
        return new ArrayCollection($query->getResult());
    }
    /**
     * Set query order
     *
     * @param $documentsQb
     * @param $orders
     */
    private function setOrder(&$documentsQb, $orders)
    {
        foreach ($orders as $order => $dir) {
            ++$this->joins;
            QueryBuilderHelper::applyOrder($documentsQb, 'q', $order, $dir, $this->joins);
        }
    }
    /**
     * Reset queryBuilder
     */
    private function resetQueryBuilder()
    {
        $this->joins            = 0;
        $this->joinsPreparation = [];
    }
}