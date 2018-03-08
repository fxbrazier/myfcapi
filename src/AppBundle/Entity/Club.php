<?php

namespace AppBundle\Entity;

use AdminBundle\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Club
 *
 * @ORM\Table(name="club")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DefaultRepository")
 */
class Club extends AbstractEntity
{
    public static $defaultFieldOrder = "name";
    public static $defaultDirOrder = "asc";
    public static $fieldsOrder = [
        'id',
        'name',
    ];
    public static $fieldsApi = [
        'id',
        'name',
        'blason',
        'clubStats',
    ];

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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="blason", type="string", length=255)
     */
    private $blason;

    /**
     * One Club has Many ClubStat.
     * @ORM\OneToMany(targetEntity="ClubStat", mappedBy="club")
     */
    private $clubStats;


    public function __construct()
    {
        $this->clubStats = new ArrayCollection();
    }

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
     * @return Club
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
     * Set blason
     *
     * @param string $blason
     *
     * @return Club
     */
    public function setBlason($blason)
    {
        $this->blason = $blason;

        return $this;
    }

    /**
     * Get blason
     *
     * @return string
     */
    public function getBlason()
    {
        return $this->blason;
    }

    /**
     * Add clubStat
     *
     * @param \AppBundle\Entity\ClubStat $clubStat
     *
     * @return Club
     */
    public function addClubStat(\AppBundle\Entity\ClubStat $clubStat)
    {
        $this->clubStats[] = $clubStat;

        return $this;
    }

    /**
     * Remove clubStat
     *
     * @param \AppBundle\Entity\ClubStat $clubStat
     */
    public function removeClubStat(\AppBundle\Entity\ClubStat $clubStat)
    {
        $this->clubStats->removeElement($clubStat);
    }

    /**
     * Get clubStats
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClubStats()
    {
        return $this->clubStats;
    }
}
