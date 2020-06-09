<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Vemid\ProjectOne\Entity\Entity;
use Vemid\ProjectOne\Common\Annotation as FormAnnotation;

/**
 * UserRoleAssignments
 *
 * @ORM\Table(name="user_role_assignments", indexes={@ORM\Index(name="role_id", columns={"role_id"}), @ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity(repositoryClass="Vemid\ProjectOne\Entity\Repository\UserRoleAssignmentRepository")
 */
class UserRoleAssignment extends Entity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userRoleAssignments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    protected $user;

    /**
     * @var Role
     *
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="userRoleAssignments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     * })
     */
    protected $role;

    /**
     * @ORM\OneToMany(targetEntity="Role", mappedBy="role")
     */
    private $roles;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();

    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set user.
     *
     * @param User|null $user
     *
     * @return UserRoleAssignment
     */
    public function setUser($user = null): UserRoleAssignment
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return User|null
     */
    public function getUser(): ?USer
    {
        return $this->user;
    }

    /**
     * Set role.
     *
     * @param Role|null $role
     *
     * @return UserRoleAssignment
     */
    public function setRole($role = null): UserRoleAssignment
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role.
     *
     * @return Role|null
     */
    public function getRole(): ?Role
    {
        return $this->role;
    }
}
