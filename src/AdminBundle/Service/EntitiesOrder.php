<?php

namespace AdminBundle\Service;

use AdminBundle\Controller\JsonController;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Service used to get current field order from request, used in document, references lists ordering
 */
class EntitiesOrder
{
    /**
     * @var array
     */
    private $orderBys;
    /**
     * Constructor
     *
     * @param \Symfony\Component\HttpFoundation\RequestStack    $requestStack
     * @param \AdminBundle\Controller\JsonController $jsonController
     */
    public function __construct()
    {
        $this->orderBys = [];
    }
    /**
     * Returns an array which can be used by the orderBy parameter in find(One)By
     *
     * @param array  $allowedFields
     * @param null   $defaultField
     * @param string $defaultOrderDir
     *
     * @return \AdminBundle\Service\EntitiesOrder
     * @internal param array $orders
     */
    public function handleOrders(
        array $orders,
        array $allowedFields,
        $defaultField = null,
        $defaultOrderDir = 'desc'
    ) {
        $this->checkDefaultField($allowedFields, $defaultField);
        foreach ($orders as $order) {
            if (!is_array($order) || !count($order) == 2) {
                continue;
            }
            $orderField = $order[0];
            $orderDir   = $order[1];
            $orderBy = $this->handleOrder($orderField, $orderDir, $defaultOrderDir, $allowedFields);
            if (!empty($orderBy)) {
                $this->orderBys[$orderBy[0]] = $orderBy[1];
            }
        }
        if (empty($this->orderBys)) {
            $orderBy        = [];
            $realOrderField = null;
            if (!empty($defaultField)) {
                $realOrderField = $defaultField;
            } elseif (!empty($allowedFields)) {
                $realOrderField = $allowedFields[0];
            }
            if ($realOrderField !== null) {
                $orderBy = [$realOrderField, $defaultOrderDir];
            }
            //$this->orderBys[$orderBy[0]] = $orderBy[1];
        }
        return $this;
    }
    /**
     * Handles one order
     *
     * @param $orderField
     * @param $orderDir
     * @param $defaultOrderDir
     *
     * @return array
     */
    private function handleOrder($orderField, $orderDir, $defaultOrderDir, $allowedFields)
    {
        $orderBy = [];
        $realOrderField = null;
        $realOrderDir   = null;
        if (!empty($allowedFields)) {
            if ($orderField && (in_array($orderField, $allowedFields))) {
                $realOrderField = $orderField;
            } else {
                if ($orderField && (in_array(":" . $orderField, $allowedFields))) {
                    $realOrderField = ":" . $orderField;
                }
            }
            if ($orderDir && ($orderDir == 'asc' || $orderDir == 'desc')) {
                $realOrderDir = $orderDir;
            } else {
                $realOrderDir = $defaultOrderDir;
            }
        }
        if ($realOrderField !== null) {
            $orderBy = [$realOrderField, $realOrderDir];
        }
        return $orderBy;
    }
    /**
     * Is default field in allowed fields ?
     *
     * @param $allowedFields
     * @param $defaultField
     *
     * @throws \Exception
     */
    private function checkDefaultField($allowedFields, $defaultField)
    {
        if ($defaultField !== null && (!in_array($defaultField, $allowedFields))) {
            throw new \Exception(
                'Default field "' . $defaultField . '" is not in allowed fields : "' .
                join(', ', $allowedFields) . '" !'
            );
        }
    }
    /**
     * Returns the array
     *
     * @return array
     */
    public function getOrders()
    {
        return $this->orderBys;
    }
}