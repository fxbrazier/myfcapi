<?php

namespace AppBundle\Entity;

use AdminBundle\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;


/**
 * Contrat
 *
 * @ORM\Table(name="contrat")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DefaultRepository")
 */
class Contrat extends AbstractEntity
{
    public static $defaultFieldOrder = "id";
    public static $defaultDirOrder = "asc";
    public static $fieldsOrder = [
        'id',
        'salaire',
        'date_start',
        'date_end',
        'on_going',
    ];
    public static $fieldsApi = [
        'id',
        'salaire',
        'description',
        'value',
        'dateStart',
        'duration',
        'onGoing',
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
     * @var int
     *
     * @ORM\Column(name="salaire", type="integer", length=4)
     */
    private $salaire;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=125)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="value", type="integer", length=2)
     */
    private $value;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_start", type="date")
     */
    private $dateStart;

    /**
     * @var integer
     *
     * @ORM\Column(name="duration", type="integer", length=2)
     */
    private $duration;

    /**
     * @var bool
     *
     * @ORM\Column(name="on_going", type="boolean")
     */
    private $onGoing;


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
     * Set salaire
     *
     * @param $salaire
     *
     * @return Contrat
     */
    public function setSalaire($salaire)
    {
        $this->salaire = $salaire;

        return $this;
    }

    /**
     * Get salaire
     *
     * @return int
     */
    public function getSalaire()
    {
        return $this->salaire;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Contrat
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set value
     *
     * @param integer $value
     *
     * @return Contrat
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set dateStart
     *
     * @param \DateTime $dateStart
     *
     * @return Contrat
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    /**
     * Get dateStart
     *
     * @return \DateTime
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * Set duration
     *
     * @param \integer $duration
     *
     * @return Contrat
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return \integer
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set onGoing
     *
     * @param boolean $onGoing
     *
     * @return Contrat
     */
    public function setOnGoing($onGoing)
    {
        $this->onGoing = $onGoing;

        return $this;
    }

    /**
     * Get onGoing
     *
     * @return bool
     */
    public function getOnGoing()
    {
        return $this->onGoing;
    }
}
