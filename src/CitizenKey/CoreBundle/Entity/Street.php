<?php

namespace CitizenKey\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Street
 *
 * @ORM\Table(name="street")
 * @ORM\Entity(repositoryClass="CitizenKey\CoreBundle\Repository\StreetRepository")
 */
class Street
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var City
     *
     * @ORM\ManyToOne(targetEntity="City")
     */
    private $city;

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
     * Set name
     *
     * @param string $name
     *
     * @return Street
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set city
     *
     * @param \CitizenKey\CoreBundle\Entity\City $city
     *
     * @return Street
     */
    public function setCity(\CitizenKey\CoreBundle\Entity\City $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \CitizenKey\CoreBundle\Entity\City
     */
    public function getCity()
    {
        return $this->city;
    }
}
