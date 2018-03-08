<?php

namespace AppBundle\Entity;

use AdminBundle\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * LeagueResult
 *
 * @ORM\Table(name="league_result")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DefaultRepository")
 */
class LeagueResult extends AbstractEntity
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
     * @return LeagueResult
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
}
