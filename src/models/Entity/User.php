<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Vemid\ProjectOne\Common\Annotation as FormAnnotation;
use Vemid\ProjectOne\Entity\Entity;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Vemid\ProjectOne\Entity\Repository\UserRepository")
 */
class User extends Entity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @FormAnnotation\FormElement(type="Hidden", required=true)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=false)
     * @FormAnnotation\FormElement(type="Text", required=true, options={"1":"A", "Vemid":"Vemid"})
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=false)
     * @FormAnnotation\FormElement(type="Text", required=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     * @FormAnnotation\FormElement(type="Email", required=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=false)
     * @FormAnnotation\FormElement(type="Text", required=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     * @FormAnnotation\FormElement(type="Password", required=false)
     */
    private $password = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true)
     * @FormAnnotation\FormElement(type="Upload", required=false)
     */
    private $avatar;

    /**
     * @var string|null
     *
     * @ORM\Column(name="gender", type="string", length=0, nullable=true)
     * @FormAnnotation\FormElement(type="Select", required=false, options={"MALE": "Male", "FEMALE" : "Female"})
     */
    private $gender;

    /**
     * @var string|null
     *
     * @ORM\Column(name="secret_key", type="string", length=255, nullable=true)
     */
    private $secretKey;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     * @FormAnnotation\FormElement(type="Checkbox", required=false)
     */
    private $isActive = false;

    /**
     * @var string|null
     *
     * @ORM\Column(name="last_ip", type="string", length=255, nullable=true)
     */
    private $lastIp;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="registered_datetime", type="datetime", nullable=true)
     */
    private $registeredDatetime;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="last_visit_datetime", type="datetime", nullable=true)
     */
    private $lastVisitDatetime;

    /**
     * @ORM\OneToMany(targetEntity="UserRoleAssignment", mappedBy="user", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $userRoleAssignments;

    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     * @ORM\JoinTable(name="user_role_assignments")
     */
    private $roles;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->userRoleAssignments = new ArrayCollection();
        $this->roles = new ArrayCollection();

    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId():? int
    {
        return $this->id;
    }

    /**
     * Set firstName.
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName): User
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName.
     *
     * @return string
     */
    public function getFirstName():? string
    {
        return $this->firstName;
    }

    /**
     * Set lastName.
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName): User
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName.
     *
     * @return string
     */
    public function getLastName():? string
    {
        return $this->lastName;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email): User
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail():? string
    {
        return $this->email;
    }

    /**
     * Set username.
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username): User
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername():? string
    {
        return $this->username;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password): User
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword():? string
    {
        return $this->password;
    }

    /**
     * Set avatar.
     *
     * @param string|null $avatar
     *
     * @return User
     */
    public function setAvatar($avatar = null): User
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar.
     *
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * Set gender.
     *
     * @param string|null $gender
     *
     * @return User
     */
    public function setGender($gender = null): User
    {
        $this->gender = $gender ?: null;

        return $this;
    }

    /**
     * Get gender.
     *
     * @return string|null
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @return string|null
     */
    public function getSecretKey(): ?string
    {
        return $this->secretKey;
    }

    /**
     * @param string|null $secretKey
     */
    public function setSecretKey(?string $secretKey): void
    {
        $this->secretKey = $secretKey;
    }

    /**
     * Set isActive.
     *
     * @param bool $isActive
     *
     * @return User
     */
    public function setIsActive($isActive): User
    {
        $this->isActive = $isActive ?: false;

        return $this;
    }

    /**
     * Get isActive.
     *
     * @return bool
     */
    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    /**
     * Set lastIp.
     *
     * @param string|null $lastIp
     *
     * @return User
     */
    public function setLastIp($lastIp = null): User
    {
        $this->lastIp = $lastIp;

        return $this;
    }

    /**
     * Get lastIp.
     *
     * @return string|null
     */
    public function getLastIp(): ?string
    {
        return $this->lastIp;
    }

    /**
     * Set registeredDatetime.
     *
     * @param \DateTime|null $registeredDatetime
     *
     * @return User
     */
    public function setRegisteredDatetime($registeredDatetime = null): User
    {
        $this->registeredDatetime = $registeredDatetime;

        return $this;
    }

    /**
     * Get registeredDatetime.
     *
     * @return \DateTime|null
     */
    public function getRegisteredDatetime(): ?\DateTime
    {
        return $this->registeredDatetime;
    }

    /**
     * Set lastVisitDatetime.
     *
     * @param \DateTime|null $lastVisitDatetime
     *
     * @return User
     */
    public function setLastVisitDatetime($lastVisitDatetime = null): User
    {
        $this->lastVisitDatetime = $lastVisitDatetime;

        return $this;
    }

    /**
     * Get lastVisitDatetime.
     *
     * @return \DateTime|null
     */
    public function getLastVisitDatetime(): ?\DateTime
    {
        return $this->lastVisitDatetime;
    }

    /**
     * @return PersistentCollection
     */
    public function getUserRoleAssignments(): ?PersistentCollection
    {
        return $this->userRoleAssignments;
    }

    /**
     * @return PersistentCollection|Role[]
     */
    public function getRoles(): ?PersistentCollection
    {
        return $this->roles;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%s %s', $this->firstName, $this->lastName);
    }
}
