<?php

namespace CitizenKey\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Phone
 *
 * @ORM\Table(name="phone")
 * @ORM\Entity(repositoryClass="CitizenKey\CoreBundle\Repository\PhoneRepository")
 */
class Phone
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
     * @ORM\Column(name="number", type="string", length=15)
     */
    private $number;

    /**
     * @var bool
     *
     * @ORM\Column(name="allowSMS", type="boolean")
     */
    private $allowSMS;

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
     * Set number
     *
     * @param string $number
     *
     * @return Phone
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
     * Set allowSMS
     *
     * @param boolean $allowSMS
     *
     * @return Phone
     */
    public function setAllowSMS($allowSMS)
    {
        $this->allowSMS = $allowSMS;

        return $this;
    }

    /**
     * Get allowSMS
     *
     * @return bool
     */
    public function getAllowSMS()
    {
        return $this->allowSMS;
    }

    /**
     * Set allowCampaign
     *
     * @param boolean $allowCampaign
     *
     * @return Phone
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
     * @return Phone
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
