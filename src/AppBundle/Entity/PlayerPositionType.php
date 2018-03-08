<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlayerPositionType
 *
 * @ORM\Table(name="player_position_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlayerPositionTypeRepository")
 */
class PlayerPositionType
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
     * @ORM\Column(name="label", type="string", length=125)
     */
    private $label;

    /**
     * @var string
     *
     * @ORM\Column(name="short_label", type="string", length=255)
     */
    private $shortLabel;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string")
     */
    private $color;


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
     * @return PlayerPositionType
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
     * @return PlayerPositionType
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
     * Set color
     *
     * @param string $color
     *
     * @return PlayerPositionType
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }
}
