<?php

namespace AppBundle\Entity;

use AdminBundle\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Jersey
 *
 * @ORM\Table(name="jersey")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DefaultRepository")
 */
class Jersey extends AbstractEntity
{

    public static $defaultFieldOrder = "id";
    public static $defaultDirOrder = "asc";
    public static $fieldsOrder = [
        'id',
        'jerseyType.label',
    ];
    public static $fieldsApi = [
        'id',
        'img',
        'jerseyType.label',
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
     * @ORM\Column(name="img", type="string", length=255)
     */
    private $img;

    /**
     * Many Jersey have One JerseyType.
     * @ORM\ManyToOne(targetEntity="JerseyType", inversedBy="jerseys")
     * @ORM\JoinColumn(name="jersey_type_id", referencedColumnName="id")
     */
    private $jerseyType;

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
     * Set img
     *
     * @param string $img
     *
     * @return Jersey
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Get img
     *
     * @return string
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * Set jerseyType
     *
     * @param \AppBundle\Entity\JerseyType $jerseyType
     *
     * @return Jersey
     */
    public function setJerseyType(\AppBundle\Entity\JerseyType $jerseyType = null)
    {
        $this->jerseyType = $jerseyType;

        return $this;
    }

    /**
     * Get jerseyType
     *
     * @return \AppBundle\Entity\JerseyType
     */
    public function getJerseyType()
    {
        return $this->jerseyType;
    }
}
