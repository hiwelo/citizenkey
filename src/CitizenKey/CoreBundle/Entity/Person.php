<?php

namespace CitizenKey\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Person
 *
 * @ORM\Table(name="person")
 * @ORM\Entity(repositoryClass="CitizenKey\CoreBundle\Repository\PersonRepository")
 */
class Person
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
     * @var Platform
     *
     * @ORM\ManyToOne(targetEntity="Platform")
     */
    private $platform;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * Returns a formatted name for the current person
     *
     * @var string
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="gender", type="smallint", nullable=true)
     */
    private $gender;

    /**
     * @var bool
     *
     * @ORM\Column(name="voter", type="boolean")
     */
    private $voter;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthdate", type="date", nullable=true)
     */
    private $birthdate;

    /**
     * @var string
     *
     * @ORM\Column(name="position", type="string", length=255, nullable=true)
     */
    private $position;

    /**
     * @var string
     *
     * @ORM\Column(name="company", type="string", length=255, nullable=true)
     */
    private $company;

    /**
     * @var string
     */
    private $avatar;

    /**
     * @var array
     *
     * @ORM\Column(name="tags", type="simple_array", nullable=true)
     */
    private $tags;

    /**
     * @var \DateTime
     * @ORM\Column(name="creationDate", type="datetime")
     */
    private $creationDate;

    /**
     * @var array
     * @ORM\OneToMany(targetEntity="Phone", mappedBy="person")
     */
    private $phones;

    /**
     * @var array
     * @ORM\OneToMany(targetEntity="EmailAddress", mappedBy="person")
     */
    private $emails;

    /**
     * @var array
     * @ORM\OneToMany(targetEntity="Address", mappedBy="person")
     */
    private $addresses;

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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Person
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Person
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Get formatted name
     *
     * @return string
     */
    public function getName()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    /**
     * Set gender
     *
     * @param integer $gender
     *
     * @return Person
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return int
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set voter
     *
     * @param boolean $voter
     *
     * @return Person
     */
    public function setVoter($voter)
    {
        $this->voter = $voter;

        return $this;
    }

    /**
     * Get voter
     *
     * @return bool
     */
    public function getVoter()
    {
        return $this->voter;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     *
     * @return Person
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set position
     *
     * @param string $position
     *
     * @return Person
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set company
     *
     * @param string $company
     *
     * @return Person
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set tags
     *
     * @param array $tags
     *
     * @return Person
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags
     *
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set platform
     *
     * @param \CitizenKey\CoreBundle\Entity\Platform $platform
     *
     * @return Person
     */
    public function setPlatform(\CitizenKey\CoreBundle\Entity\Platform $platform = null)
    {
        $this->platform = $platform;

        return $this;
    }

    /**
     * Get platform
     *
     * @return \CitizenKey\CoreBundle\Entity\Platform
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * Get a Gravatar URL for the person
     *
     * @return string
     */
    public function getAvatar()
    {
        return '';
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->phones = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add phone
     *
     * @param \CitizenKey\CoreBundle\Entity\Phone $phone
     *
     * @return Person
     */
    public function addPhone(\CitizenKey\CoreBundle\Entity\Phone $phone)
    {
        $this->phones[] = $phone;

        return $this;
    }

    /**
     * Remove phone
     *
     * @param \CitizenKey\CoreBundle\Entity\Phone $phone
     */
    public function removePhone(\CitizenKey\CoreBundle\Entity\Phone $phone)
    {
        $this->phones->removeElement($phone);
    }

    /**
     * Get phones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return Person
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Add email
     *
     * @param \CitizenKey\CoreBundle\Entity\EmailAddress $email
     *
     * @return Person
     */
    public function addEmail(\CitizenKey\CoreBundle\Entity\EmailAddress $email)
    {
        $this->emails[] = $email;

        return $this;
    }

    /**
     * Remove email
     *
     * @param \CitizenKey\CoreBundle\Entity\EmailAddress $email
     */
    public function removeEmail(\CitizenKey\CoreBundle\Entity\EmailAddress $email)
    {
        $this->emails->removeElement($email);
    }

    /**
     * Get emails
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmails()
    {
        return $this->emails;
    }

    /**
     * Add address
     *
     * @param \CitizenKey\CoreBundle\Entity\Address $address
     *
     * @return Person
     */
    public function addAddress(\CitizenKey\CoreBundle\Entity\Address $address)
    {
        $this->addresses[] = $address;

        return $this;
    }

    /**
     * Remove address
     *
     * @param \CitizenKey\CoreBundle\Entity\Address $address
     */
    public function removeAddress(\CitizenKey\CoreBundle\Entity\Address $address)
    {
        $this->addresses->removeElement($address);
    }

    /**
     * Get addresses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAddresses()
    {
        return $this->addresses;
    }
}
