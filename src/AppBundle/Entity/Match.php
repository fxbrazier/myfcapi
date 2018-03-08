<?php

namespace AppBundle\Entity;

use AdminBundle\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Match
 *
 * @ORM\Table(name="match")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DefaultRepository")
 */
class Match extends AbstractEntity
{
    public static $defaultFieldOrder = "id";
    public static $defaultDirOrder = "asc";
    public static $fieldsOrder = [
        'id',
    ];
    public static $fieldsApi = [
        'id',
        'result',
        'winner',
        'loser',
        'date',
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
     * @ORM\Column(name="result", type="string", length=125)
     */
    private $result;

    /**
     * @var string
     *
     * @ORM\Column(name="winner", type="string", length=125)
     */
    private $winner;

    /**
     * @var string
     *
     * @ORM\Column(name="loser", type="string")
     */
    private $loser;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;


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
     * Set result
     *
     * @param string $result
     *
     * @return Match
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get result
     *
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set winner
     *
     * @param string $winner
     *
     * @return Match
     */
    public function setWinner($winner)
    {
        $this->winner = $winner;

        return $this;
    }

    /**
     * Get winner
     *
     * @return string
     */
    public function getWinner()
    {
        return $this->winner;
    }

    /**
     * Set loser
     *
     * @param string $loser
     *
     * @return Match
     */
    public function setLoser($loser)
    {
        $this->loser = $loser;

        return $this;
    }

    /**
     * Get loser
     *
     * @return string
     */
    public function getLoser()
    {
        return $this->loser;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Match
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}
