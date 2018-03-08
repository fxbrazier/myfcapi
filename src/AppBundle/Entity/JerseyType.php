<?php

namespace AppBundle\Entity;

use AdminBundle\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * JerseyType
 *
 * @ORM\Table(name="jersey_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DefaultRepository")
 */
class JerseyType extends AbstractEntity
{

    public static $defaultFieldOrder = "label";
    public static $defaultDirOrder = "asc";
    public static $fieldsOrder = [
        'id',
        'label',
    ];
    public static $fieldsApi = [
        'id',
        'label',
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
     * @ORM\Column(name="label", type="string", length=125)
     */
    private $label;

    /**
     * One JerseyType has Many Jerseys.
     * @ORM\OneToMany(targetEntity="Jersey", mappedBy="jerseyType")
     */
    private $jerseys;


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
     * Set label
     *
     * @param string $label
     *
     * @return JerseyType
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->jerseys = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add jersey
     *
     * @param \AppBundle\Entity\Jersey $jersey
     *
     * @return JerseyType
     */
    public function addJersey(\AppBundle\Entity\Jersey $jersey)
    {
        $this->jerseys[] = $jersey;

        return $this;
    }

    /**
     * Remove jersey
     *
     * @param \AppBundle\Entity\Jersey $jersey
     */
    public function removeJersey(\AppBundle\Entity\Jersey $jersey)
    {
        $this->jerseys->removeElement($jersey);
    }

    /**
     * Get jerseys
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getJerseys()
    {
        return $this->jerseys;
    }
}
