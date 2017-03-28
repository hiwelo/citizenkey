<?php

namespace CitizenKey\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Email address
 *
 * @ORM\Table(name="email_address")
 * @ORM\Entity(repositoryClass="CitizenKey\CoreBundle\Repository\EmailAddressRepository")
 */
class EmailAddress
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
     * @ORM\Column(name="address", type="string", length=254)
     */
    private $address;

    /**
     * @var bool
     *
     * @ORM\Column(name="allowEmail", type="boolean")
     */
    private $allowEmail;

    /**
     * @var bool
     *
     * @ORM\Column(name="allowCampaign", type="boolean")
     */
    private $allowCampaign;

    /**
     * @var Person
     *
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="phones")
     */
    private $person;


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
     * Set address
     *
     * @param string $address
     *
     * @return string
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set allowEmail
     *
     * @param boolean $allowEmail
     *
     * @return Email
     */
    public function setAllowEmail($allowEmail)
    {
        $this->allowEmail = $allowEmail;

        return $this;
    }

    /**
     * Get allowEmail
     *
     * @return bool
     */
    public function getAllowEmail()
    {
        return $this->allowEmail;
    }

    /**
     * Set allowCampaign
     *
     * @param boolean $allowCampaign
     *
     * @return Email
     */
    public function setAllowCampaign($allowCampaign)
    {
        $this->allowCampaign = $allowCampaign;

        return $this;
    }

    /**
     * Get allowCampaign
     *
     * @return bool
     */
    public function getAllowCampaign()
    {
        return $this->allowCampaign;
    }

    /**
     * Set person
     *
     * @param \CitizenKey\CoreBundle\Entity\Person $person
     *
     * @return Email
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
}
