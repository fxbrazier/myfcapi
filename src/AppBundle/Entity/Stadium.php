<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stadium
 *
 * @ORM\Table(name="stadium")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StadiumRepository")
 */
class Stadium
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=125)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="current_capacity", type="integer", length=6)
     */
    private $current_capacity;

    /**
     * @var int
     *
     * @ORM\Column(name="maximum_capacity", type="integer", length=6)
     */
    private $maximum_capacity;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Stadium
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set currentCapacity
     *
     * @param integer $currentCapacity
     *
     * @return Stadium
     */
    public function setCurrentCapacity($currentCapacity)
    {
        $this->current_capacity = $currentCapacity;

        return $this;
    }

    /**
     * Get currentCapacity
     *
     * @return int
     */
    public function getCurrentCapacity()
    {
        return $this->current_capacity;
    }

    /**
     * Set maximumCapacity
     *
     * @param integer $maximumCapacity
     *
     * @return Stadium
     */
    public function setMaximumCapacity($maximumCapacity)
    {
        $this->maximum_capacity = $maximumCapacity;

        return $this;
    }

    /**
     * Get maximumCapacity
     *
     * @return int
     */
    public function getMaximumCapacity()
    {
        return $this->maximum_capacity;
    }
}
