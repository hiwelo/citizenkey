<?php

namespace CitizenKey\CoreBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="CitizenKey\CoreBundle\Repository\UserRepository")
 */
class User implements UserInterface
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
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=150, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="simple_array")
     */
    private $roles = ['ROLE_USER'];

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=60)
     */
    private $password;

    /**
     * @var bool
     *
     * @ORM\Column(name="activation", type="boolean")
     */
    private $activation;

    /**
     * Returns a gravatar for the user's email or a generic avatar
     *
     * @var string
     *
     * @ORM\Column(name="avatar", type="text", nullable=true)
     */
    private $avatar = null;

    /**
     * @var Platform
     *
     * @ORM\OneToMany(targetEntity="Platform", mappedBy="owner")
     */
    private $platforms;

    /**
     * @var Subscription
     *
     * @ORM\OneToMany(targetEntity="Subscription", mappedBy="user")
     */
    private $subscriptions;

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
     * Set a unique username for the current user
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username = null)
    {
        if ($username === null) {
            $username = uniqid();
        }

        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
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
     * Set roles
     *
     * @param array $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Hash and set a non-hashed password
     *
     * @param string $plainPassword non hashed password
     *
     * @return User
     */
    public function setNewPassword($plainPassword)
    {
        $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);

        return $this->setPassword($hashedPassword);
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set activation
     *
     * @param boolean $activation
     *
     * @return User
     */
    public function setActivation($activation)
    {
        $this->activation = $activation;

        return $this;
    }

    /**
     * Get activation
     *
     * @return bool
     */
    public function getActivation()
    {
        return $this->activation;
    }

    /**
     * Get salt
     *
     * @return void
     */
    public function getSalt()
    {
    }

    /**
     * Erase Credentials
     *
     * @return void
     */
    public function eraseCredentials()
    {
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->platforms = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add platform
     *
     * @param \CitizenKey\CoreBundle\Entity\Platform $platform
     *
     * @return User
     */
    public function addPlatform(\CitizenKey\CoreBundle\Entity\Platform $platform)
    {
        $this->platforms[] = $platform;

        return $this;
    }

    /**
     * Remove platform
     *
     * @param \CitizenKey\CoreBundle\Entity\Platform $platform
     */
    public function removePlatform(\CitizenKey\CoreBundle\Entity\Platform $platform)
    {
        $this->platforms->removeElement($platform);
    }

    /**
     * Get platforms
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlatforms()
    {
        return $this->platforms;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        // if there's any avatar URL in the database, we search for a gravatar
        if (is_null($this->avatar) && null !== $this->getEmail()) {
            $gravatar = [
                'https://www.gravatar.com/avatar/',
                md5($this->getEmail()),
                '?s=200',
                '&d=identicon',
                '&r=g',
            ];

            return implode($gravatar);
        }

        return $this->avatar;
    }

    /**
     * Add subscription
     *
     * @param \CitizenKey\CoreBundle\Entity\Subscription $subscription
     *
     * @return User
     */
    public function addSubscription(\CitizenKey\CoreBundle\Entity\Subscription $subscription)
    {
        $this->subscriptions[] = $subscription;

        return $this;
    }

    /**
     * Remove subscription
     *
     * @param \CitizenKey\CoreBundle\Entity\Subscription $subscription
     */
    public function removeSubscription(\CitizenKey\CoreBundle\Entity\Subscription $subscription)
    {
        $this->subscriptions->removeElement($subscription);
    }

    /**
     * Get subscriptions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }
}
