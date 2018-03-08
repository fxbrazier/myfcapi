<?php

namespace AdminBundle\Helper\ORM;

use Doctrine\ORM\QueryBuilder;
use DoctrineExtensions\Query\Mysql\StrToDate;

/**
 * Helps build extended findBy queries
 */
abstract class QueryBuilderHelper
{
    /**
     * Adds criteria to query builder
     *
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param                            $selectChar
     * @param                            $field
     * @param                            $val
     * @param                            $joinNumber
     * @param                            $joinsPreparation
     */
    public static function applyCriteria(QueryBuilder &$qb, $selectChar, $field, $val, &$joinNumber, &$joinsPreparation)
    {
        if (strpos($field, '.') !== false) {
            $parts = explode('.', $field);
            if (count($parts) > 2) {
                $previousAlias = $selectChar;
                for ($i = 0; $i < count($parts) - 2; ++$i) {
                    $joinAlias = 'join' . $joinNumber;
                    $qb->innerJoin(
                        $previousAlias . '.' . $parts[$i],
                        $joinAlias
                    );
                    $previousAlias = $joinAlias;
                    ++$joinNumber;
                }
                self::collectJoinCriteria(
                    $joinAlias,
                    $parts[$i],
                    $parts[++$i],
                    $val,
                    $joinNumber,
                    $joinsPreparation
                );
            } else {
                self::collectJoinCriteria($selectChar, $parts[0], $parts[1], $val, $joinNumber, $joinsPreparation);
            }
        } else {
            self::applySimpleCriteria($qb, $selectChar, $field, $val, $joinNumber);
        }
    }
    /**
     * Collect Join criteria
     *
     * @param                            $selectChar
     * @param                            $tableToJoin
     * @param                            $fieldToJoin
     * @param                            $val
     * @param                            $joinNumber
     * @param                            $joinsPreparation
     */
    public static function collectJoinCriteria(
        $selectChar,
        $tableToJoin,
        $fieldToJoin,
        $val,
        &$joinNumber,
        &$joinsPreparation
    ) {
        $wheres     = self::prepareValue($val);
        $conditions = [];
        $parameters = [];
        if (isset($joinsPreparation[$selectChar . '.' . $tableToJoin][0])) {
            $joinAlias = $joinsPreparation[$selectChar . '.' . $tableToJoin][0];
        } else {
            $joinAlias = 'join' . ++$joinNumber;
        }
        foreach ($wheres as $crit) {
            $fieldAlias = 'field' . ++$joinNumber;
            $op         = current(array_keys($crit));
            if (($op == 'IN' || $op == 'NOT IN') && is_string($crit[$op])) {
                $conditions[] = self::createConditionPart($joinAlias, $fieldToJoin, $op, null, $crit[$op]);
            } else {
                $conditions[]            = self::createConditionPart($joinAlias, $fieldToJoin, $op, $fieldAlias);
                $parameters[$fieldAlias] = $crit[$op];
            }
        }
        if (isset($joinsPreparation[$selectChar . '.' . $tableToJoin])) {
            $joinsPreparation[$selectChar . '.' . $tableToJoin][1] =
                array_merge($conditions, $joinsPreparation[$selectChar . '.' . $tableToJoin][1]);
            $joinsPreparation[$selectChar . '.' . $tableToJoin][2] =
                array_merge($parameters, $joinsPreparation[$selectChar . '.' . $tableToJoin][2]);
        } else {
            $joinsPreparation[$selectChar . '.' . $tableToJoin] = [
                $joinAlias,
                $conditions,
                $parameters,
            ];
        }
    }
    /**
     * Prepares a WHERE field value
     *
     * @param $val
     *
     * @return array
     */
    public static function prepareValue($val)
    {
        if (is_array($val)) {
            if (count($val) > 1) {
                $wheres = $val;
            } else {
                $wheres = [$val];
            }
        } else {
            $wheres = [['=' => $val]];
        }
        return $wheres;
    }
    /**
     * Create SQL condition part
     *
     * @param $tableAlias
     * @param $field
     * @param $op
     * @param $fieldAlias
     * @param $rawValue
     *
     * @return string
     * @throws \Exception
     */
    public static function createConditionPart($tableAlias, $field, $op, $fieldAlias = null, $rawValue = null)
    {
        self::checkOperator($op);
        return $tableAlias . '.' . $field . ' ' . $op
            . ($op == 'IN' || $op == 'NOT IN' ? '(' : '')
            . ($fieldAlias !== null ? ' :' . $fieldAlias : $rawValue)
            . ($op == 'IN' || $op == 'NOT IN' ? ')' : '');
    }
    /**
     * Checks conditional operator
     *
     * @param $op
     *
     * @throws \Exception
     */
    private static function checkOperator($op)
    {
        $authorizedOperators = [
            '=',
            '<=',
            '>=',
            '<',
            '>',
            '<>',
            'IN',
            'NOT IN',
            'LIKE',
        ];
        if (!in_array($op, $authorizedOperators)) {
            throw new \Exception('Unauthorized operator "' . $op . '" !');
        }
    }
    /**
     * Apply Simple Criteria
     *
     * @param $qb
     * @param $selectChar
     * @param $field
     * @param $val
     * @param $joinNumber
     */
    public static function applySimpleCriteria(QueryBuilder &$qb, $selectChar, $field, $val, &$joinNumber)
    {
        if ($val !== 'NULL') {
            $wheres = self::prepareValue($val);
            foreach ($wheres as $crit) {
                $op = current(array_keys($crit));
                self::addWhere(
                    $qb,
                    $selectChar,
                    $field,
                    $op,
                    $crit[$op],
                    $joinNumber
                );
            }
        } else {
            $qb->andWhere($selectChar . '.' . $field . ' IS NULL');
        }
    }
    /**
     * Adds a custom Where to the Query Builder
     *
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param                            $selectChar
     * @param                            $field
     * @param                            $op
     * @param                            $val
     * @param                            $joinNumber
     * @param string                     $condition
     *
     * @throws \Exception
     */
    private static function addWhere(
        QueryBuilder &$qb,
        $selectChar,
        $field,
        $op,
        $val,
        &$joinNumber,
        $condition = 'AND'
    ) {
        self::checkOperator($op);
        $fieldAlias = 'field' . ++$joinNumber;
        if ($op == 'IN' || $op == 'NOT IN') {
            $qb->{strtolower($condition) . 'Where'}(
                $selectChar . '.' . $field . ' ' . $op
                . ' (' . (is_array($val) ? ' :' . $fieldAlias : $val) . ')'
            );
            if (is_array($val)) {
                $qb->setParameter($fieldAlias, $val);
            }
        } else {
            $qb->{strtolower($condition) . 'Where'}(
                $selectChar . '.' . $field . ' ' . $op . ' :' . $fieldAlias
            );
            $qb->setParameter($fieldAlias, $val);
        }
    }
    /**
     * Apply prepared join criteria
     *
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param                            $joinsPreparation
     */
    public static function applyJoinCriterias(QueryBuilder &$qb, $joinsPreparation)
    {
        foreach ($joinsPreparation as $join => $data) {
            $qb->innerJoin(
                $join,
                $data[0],
                'WITH',
                join(' AND ', $data[1])
            );
            foreach ($data[2] as $fieldAlias => $val) {
                $qb->setParameter($fieldAlias, $val);
            }
        }
    }
    /**
     * Adds order to query builder
     *
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param                            $selectChar
     * @param                            $order
     * @param                            $dir
     * @param                            $joinNumber
     */
    public static function applyOrder(QueryBuilder &$qb, $selectChar, $order, $dir, &$joinNumber)
    {
        if (strpos($order, '.') !== false) {
            $joinAlias = 'join' . $joinNumber;
            $join      = explode('.', $order);
            $qb->leftJoin($selectChar . '.' . $join[0], $joinAlias);
            $qb->addOrderBy($joinAlias . '.' . $join[1], $dir);
        } else {
            if (strpos($order, ':') !== false) {
                $qb->addOrderBy(str_replace(":", "", $order), $dir);
            } else {
                $qb->addOrderBy($selectChar . '.' . $order, $dir);
            }
        }
    }
}