<?php

namespace CitizenKey\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ZipCode
 *
 * @ORM\Table(name="zip_code")
 * @ORM\Entity(repositoryClass="CitizenKey\CoreBundle\Repository\ZipCodeRepository")
 */
class ZipCode
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
     * @ORM\Column(name="zipcode", type="string", length=255)
     */
    private $zipcode;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Country")
     */
    private $country;

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
     * Set zipcode
     *
     * @param string $zipcode
     *
     * @return ZipCode
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return string
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set country
     *
     * @param \CitizenKey\CoreBundle\Entity\Country $country
     *
     * @return ZipCode
     */
    public function setCountry(\CitizenKey\CoreBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \CitizenKey\CoreBundle\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }
}
