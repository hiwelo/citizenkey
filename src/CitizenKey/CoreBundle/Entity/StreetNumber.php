<?php

namespace CitizenKey\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StreetNumber
 *
 * @ORM\Table(name="street_number")
 * @ORM\Entity(repositoryClass="CitizenKey\CoreBundle\Repository\StreetNumberRepository")
 */
class StreetNumber
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
     * @ORM\Column(name="number", type="string", length=255)
     */
    private $number;

    /**
     * @var Street
     *
     * @ORM\ManyToOne(targetEntity="Street")
     */
    private $street;

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
     * Set number
     *
     * @param string $number
     *
     * @return StreetNumber
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set street
     *
     * @param \CitizenKey\CoreBundle\Entity\Street $street
     *
     * @return StreetNumber
     */
    public function setStreet(\CitizenKey\CoreBundle\Entity\Street $street = null)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return \CitizenKey\CoreBundle\Entity\Street
     */
    public function getStreet()
    {
        return $this->street;
    }
}
