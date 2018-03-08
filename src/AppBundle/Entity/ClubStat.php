<?php

namespace AppBundle\Entity;

use AdminBundle\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * ClubStat
 *
 * @ORM\Table(name="club_stat")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DefaultRepository")
 */
class ClubStat extends AbstractEntity
{
    public static $defaultFieldOrder = "label";
    public static $defaultDirOrder = "asc";
    public static $fieldsOrder = [
        'id',
        'label',
        'club.name'
    ];
    public static $fieldsApi = [
        'id',
        'label',
        'shortLabel',
        'club.name',
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
     * @var string
     *
     * @ORM\Column(name="short_label", type="string", length=5)
     */
    private $shortLabel;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;

    /**
     * Many ClubStat have One Club.
     * @ORM\ManyToOne(targetEntity="Club", inversedBy="clubStats")
     * @ORM\JoinColumn(name="club_id", referencedColumnName="id")
     */
    private $club;

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
     * @return ClubStat
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
     * Set shortLabel
     *
     * @param string $shortLabel
     *
     * @return ClubStat
     */
    public function setShortLabel($shortLabel)
    {
        $this->shortLabel = $shortLabel;

        return $this;
    }

    /**
     * Get shortLabel
     *
     * @return string
     */
    public function getShortLabel()
    {
        return $this->shortLabel;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return ClubStat
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set club
     *
     * @param \AppBundle\Entity\Club $club
     *
     * @return ClubStat
     */
    public function setClub(\AppBundle\Entity\Club $club = null)
    {
        $this->club = $club;

        return $this;
    }

    /**
     * Get club
     *
     * @return \AppBundle\Entity\Club
     */
    public function getClub()
    {
        return $this->club;
    }
}
