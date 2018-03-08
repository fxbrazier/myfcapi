<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Player
 *
 * @ORM\Table(name="player")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlayerRepository")
 */
class Player
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
     * @ORM\Column(name="last_name", type="string", length=125)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=125)
     */
    private $firstName;

    /**
     * @var int
     *
     * @ORM\Column(name="height", type="integer", length=3)
     */
    private $height;

    /**
     * @var int
     *
     * @ORM\Column(name="weight", type="integer", length=3)
     */
    private $weight;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="real_club", type="string", length=125)
     */
    private $realClub;

    /**
     * Many Players have Many PlayerPostions.
     * @ORM\ManyToMany(targetEntity="PlayerPosition", inversedBy="players")
     * @ORM\JoinTable(name="players_postions")
     */
    private $positions;

    /**
     * Many Players have one Nationality.
     * @ORM\ManyToOne(targetEntity="Nationality", inversedBy="players")
     * @ORM\JoinColumn(name="nationality_id", referencedColumnName="id")
     */
    private $nationality;


    public function __construct()
    {
        $this->positions = new ArrayCollection();
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
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Player
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Player
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Player
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set realClub
     *
     * @param string $realClub
     *
     * @return Player
     */
    public function setRealClub($realClub)
    {
        $this->realClub = $realClub;

        return $this;
    }

    /**
     * Get realClub
     *
     * @return string
     */
    public function getRealClub()
    {
        return $this->realClub;
    }

    /**
     * Set height
     *
     * @param integer $height
     *
     * @return Player
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     *
     * @return Player
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return integer
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Add position
     *
     * @param \AppBundle\Entity\PlayerPosition $position
     *
     * @return Player
     */
    public function addPosition(\AppBundle\Entity\PlayerPosition $position)
    {
        $this->positions[] = $position;

        return $this;
    }

    /**
     * Remove position
     *
     * @param \AppBundle\Entity\PlayerPosition $position
     */
    public function removePosition(\AppBundle\Entity\PlayerPosition $position)
    {
        $this->positions->removeElement($position);
    }

    /**
     * Get positions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPositions()
    {
        return $this->positions;
    }

    /**
     * Set nationality
     *
     * @param \AppBundle\Entity\Nationality $nationality
     *
     * @return Player
     */
    public function setNationality(\AppBundle\Entity\Nationality $nationality = null)
    {
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * Get nationality
     *
     * @return \AppBundle\Entity\Nationality
     */
    public function getNationality()
    {
        return $this->nationality;
    }
}
