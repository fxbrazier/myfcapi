<?php

namespace AppBundle\Entity;

use AdminBundle\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Friend
 *
 * @ORM\Table(name="friend")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DefaultRepository")
 */
class Friend extends AbstractEntity
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
        'blason',
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
     * @ORM\Column(name="flag", type="string", length=255)
     */
    private $flag;


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
     * @return Friend
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
     * @return Friend
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
     * @return Friend
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
}
