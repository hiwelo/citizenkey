<?php

namespace CitizenKey\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Address
 *
 * @ORM\Table(name="address")
 * @ORM\Entity(repositoryClass="CitizenKey\CoreBundle\Repository\AddressRepository")
 */
class Address
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
     * @var Person
     *
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="addresses")
     */
    private $person;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", length=10, nullable=true)
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", length=10, nullable=true)
     */
    private $longitude;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Country")
     */
    private $country;

    /**
     * @var ZipCode
     *
     * @ORM\ManyToOne(targetEntity="ZipCode")
     */
    private $zipcode;

    /**
     * @var City
     *
     * @ORM\ManyToOne(targetEntity="City")
     */
    private $city;

    /**
     * @var City
     *
     * @ORM\ManyToOne(targetEntity="Street")
     */
    private $street;

    /**
     * @var City
     *
     * @ORM\ManyToOne(targetEntity="StreetNumber")
     */
    private $streetNumber;

    /**
     * @var AdminLevel
     *
     * @ORM\ManyToOne(targetEntity="AdminLevel")
     */
    private $adminLevel1;

    /**
     * @var AdminLevel
     *
     * @ORM\ManyToOne(targetEntity="AdminLevel")
     */
    private $adminLevel2;

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
     * Set person
     *
     * @param \CitizenKey\CoreBundle\Entity\Person $person
     *
     * @return Address
     */
    public function setPerson(\CitizenKey\CoreBundle\Entity\Person $person = null)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \CitizenKey\CoreBundle\Entity\Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return Address
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return Address
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set country
     *
     * @param \CitizenKey\CoreBundle\Entity\Country $country
     *
     * @return Address
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

    /**
     * Set zipcode
     *
     * @param \CitizenKey\CoreBundle\Entity\ZipCode $zipcode
     *
     * @return Address
     */
    public function setZipcode(\CitizenKey\CoreBundle\Entity\ZipCode $zipcode = null)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return \CitizenKey\CoreBundle\Entity\ZipCode
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set city
     *
     * @param \CitizenKey\CoreBundle\Entity\City $city
     *
     * @return Address
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

    /**
     * Set street
     *
     * @param \CitizenKey\CoreBundle\Entity\Street $street
     *
     * @return Address
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

    /**
     * Set streetNumber
     *
     * @param \CitizenKey\CoreBundle\Entity\StreetNumber $streetNumber
     *
     * @return Address
     */
    public function setStreetNumber(\CitizenKey\CoreBundle\Entity\StreetNumber $streetNumber = null)
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    /**
     * Get streetNumber
     *
     * @return \CitizenKey\CoreBundle\Entity\StreetNumber
     */
    public function getStreetNumber()
    {
        return $this->streetNumber;
    }

    /**
     * Get Platform
     *
     * @return \CitizenKey\CoreBundle\Entity\Platform
     */
    public function getPlatform()
    {
        return $this->getPerson()->getPlatform();
    }

    /**
     * Set adminLevel1
     *
     * @param \CitizenKey\CoreBundle\Entity\AdminLevel $adminLevel1
     *
     * @return Address
     */
    public function setAdminLevel1(\CitizenKey\CoreBundle\Entity\AdminLevel $adminLevel1 = null)
    {
        $this->adminLevel1 = $adminLevel1;

        return $this;
    }

    /**
     * Get adminLevel1
     *
     * @return \CitizenKey\CoreBundle\Entity\AdminLevel
     */
    public function getAdminLevel1()
    {
        return $this->adminLevel1;
    }

    /**
     * Set adminLevel2
     *
     * @param \CitizenKey\CoreBundle\Entity\AdminLevel $adminLevel2
     *
     * @return Address
     */
    public function setAdminLevel2(\CitizenKey\CoreBundle\Entity\AdminLevel $adminLevel2 = null)
    {
        $this->adminLevel2 = $adminLevel2;

        return $this;
    }

    /**
     * Get adminLevel2
     *
     * @return \CitizenKey\CoreBundle\Entity\AdminLevel
     */
    public function getAdminLevel2()
    {
        return $this->adminLevel2;
    }
}
