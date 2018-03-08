<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Nationality
 *
 * @ORM\Table(name="nationality")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NationalityRepository")
 */
class Nationality
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
     * @ORM\Column(name="short_label", type="string", length=5)
     */
    private $shortLabel;

    /**
     * @var string
     *
     * @ORM\Column(name="flag", type="string", length=255)
     */
    private $flag;

    /**
     * One Nationality has many Players.
     * @ORM\OneToMany(targetEntity="Player", mappedBy="nationality")
     */
    private $players;



    public function __construct() {
        $this->players = new ArrayCollection();
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
     * Set label
     *
     * @param string $label
     *
     * @return Nationality
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
     * @return Nationality
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
     * Set flag
     *
     * @param string $flag
     *
     * @return Nationality
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;

        return $this;
    }

    /**
     * Get flag
     *
     * @return string
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * Add player
     *
     * @param \AppBundle\Entity\Player $player
     *
     * @return Nationality
     */
    public function addPlayer(\AppBundle\Entity\Player $player)
    {
        $this->players[] = $player;

        return $this;
    }

    /**
     * Remove player
     *
     * @param \AppBundle\Entity\Player $player
     */
    public function removePlayer(\AppBundle\Entity\Player $player)
    {
        $this->players->removeElement($player);
    }

    /**
     * Get players
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlayers()
    {
        return $this->players;
    }
}
