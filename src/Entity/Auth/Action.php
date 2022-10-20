<?php

namespace App\Entity\Auth;

use App\Repository\Auth\ActionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Njeaner\Symfrop\Entity\Contract\ActionInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ActionRepository::class)]
#[UniqueEntity('name')]
#[UniqueEntity('title')]
class Action implements ActionInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $title = null;

    #[ORM\Column]
    private bool $isUpdatable = true;

    #[ORM\Column]
    private bool $isIndex = false;

    #[ORM\ManyToMany(targetEntity: Role::class, mappedBy: 'roles')]
    private Collection $roles;

    #[ORM\Column]
    private bool $hasAuth = true;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $condition = null;

    #[ORM\Column(nullable: true, length: 1)]
    private ?int $conditionOption = null;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getIsUpdatable(): bool
    {
        return $this->isUpdatable;
    }

    public function setIsUpdatable(bool $isUpdatable): self
    {
        $this->isUpdatable = $isUpdatable;

        return $this;
    }

    public function getIsIndex(): bool
    {
        return $this->isIndex;
    }

    public function setIsIndex(bool $isIndex): self
    {
        $this->isIndex = $isIndex;

        return $this;
    }


    /**
     * @return Collection<int, Role>
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
            $role->addAction($this);
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        if ($this->roles->removeElement($role)) {
            $role->removeAction($this);
        }

        return $this;
    }

    public function getHasAuth(): bool
    {
        return $this->hasAuth;
    }

    public function setHasAuth(bool $hasAuth): self
    {
        $this->hasAuth = $hasAuth;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getCondition(): ?string
    {
        return $this->condition;
    }

    public function setCondition(?string $condition): self
    {
        $this->condition = $condition;

        return $this;
    }


    public function getConditionOption(): ?int
    {
        return $this->conditionOption;
    }

    public function setConditionOption(?int $conditionOption): self
    {
        $this->conditionOption = $conditionOption;

        return $this;
    }
}
