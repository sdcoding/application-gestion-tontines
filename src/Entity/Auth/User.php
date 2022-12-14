<?php

namespace App\Entity\Auth;

use App\Entity\Accounts\Account;
use App\Entity\Accounts\Fund;
use App\Repository\Auth\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Njeaner\Symfrop\Entity\Contract\ActionInterface;
use Njeaner\Symfrop\Entity\Contract\RoleInterface;
use Njeaner\Symfrop\Entity\Contract\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $pseudo = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(nullable: true)]
    private ?string $password = null;

    #[ORM\Column(length: 150)]
    private ?string $username = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 18, nullable: true)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?bool $isLocked = false;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $lockedAt = null;

    #[ORM\ManyToOne(targetEntity: self::class)]
    private ?self $admin = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'parraineds')]
    private ?self $parrain = null;

    #[ORM\OneToMany(mappedBy: 'parrain', targetEntity: self::class)]
    private Collection $parraineds;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Role $role = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $email = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Account $account = null;

    #[ORM\OneToMany(mappedBy: 'admin', targetEntity: Account::class)]
    private Collection $createdAccounts;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Fund::class, orphanRemoval: true)]
    private Collection $funds;

    #[ORM\OneToMany(mappedBy: 'admin', targetEntity: Fund::class)]
    private Collection $createdFunds;

    public function __construct()
    {
        $this->parraineds = new ArrayCollection();
        $this->setCreatedAt(new DateTimeImmutable());
        $this->setUpdatedAt($this->getCreatedAt());
        $this->createdAccounts = new ArrayCollection();
        $this->funds = new ArrayCollection();
        $this->createdFunds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->pseudo;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return [$this->getRole()?->getName()];
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function isIsLocked(): ?bool
    {
        return $this->isLocked;
    }

    public function setIsLocked(bool $isLocked): self
    {
        $this->isLocked = $isLocked;

        return $this;
    }

    public function getLockedAt(): ?\DateTimeImmutable
    {
        return $this->lockedAt;
    }

    public function setLockedAt(?\DateTimeImmutable $lockedAt): self
    {
        $this->lockedAt = $lockedAt;

        return $this;
    }

    public function getAdmin(): ?self
    {
        return $this->admin;
    }

    public function setAdmin(?self $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

    public function getParrain(): ?self
    {
        return $this->parrain;
    }

    public function setParrain(?self $parrain): self
    {
        $this->parrain = $parrain;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getParraineds(): Collection
    {
        return $this->parraineds;
    }

    public function addParrained(self $parrained): self
    {
        if (!$this->parraineds->contains($parrained)) {
            $this->parraineds[] = $parrained;
            $parrained->setParrain($this);
        }

        return $this;
    }

    public function removeParrained(self $parrained): self
    {
        if ($this->parraineds->removeElement($parrained)) {
            // set the owning side to null (unless already changed)
            if ($parrained->getParrain() === $this) {
                $parrained->setParrain(null);
            }
        }

        return $this;
    }

    public function getRole(): ?RoleInterface
    {
        return $this->role;
    }

    public function setRole(?RoleInterface $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getActions(): array
    {
        return $this->getRole()->getActions();
    }

    public function hasAction(ActionInterface $action): bool
    {
        return $this->getRole()->hasAction($action);
    }

    public function __toString(): string
    {
        return $this->username;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): self
    {
        // set the owning side of the relation if necessary
        if ($account->getUser() !== $this) {
            $account->setUser($this);
        }

        $this->account = $account;

        return $this;
    }

    /**
     * @return Collection<int, Account>
     */
    public function getCreatedAccounts(): Collection
    {
        return $this->createdAccounts;
    }

    public function addCreatedAccount(Account $createdAccount): self
    {
        if (!$this->createdAccounts->contains($createdAccount)) {
            $this->createdAccounts[] = $createdAccount;
            $createdAccount->setAdmin($this);
        }

        return $this;
    }

    public function removeCreatedAccount(Account $createdAccount): self
    {
        if ($this->createdAccounts->removeElement($createdAccount)) {
            // set the owning side to null (unless already changed)
            if ($createdAccount->getAdmin() === $this) {
                $createdAccount->setAdmin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Fund>
     */
    public function getFunds(): Collection
    {
        return $this->funds;
    }

    public function addFund(Fund $fund): self
    {
        if (!$this->funds->contains($fund)) {
            $this->funds[] = $fund;
            $fund->setUser($this);
        }

        return $this;
    }

    public function removeFund(Fund $fund): self
    {
        if ($this->funds->removeElement($fund)) {
            // set the owning side to null (unless already changed)
            if ($fund->getUser() === $this) {
                $fund->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Fund>
     */
    public function getCreatedFunds(): Collection
    {
        return $this->createdFunds;
    }

    public function addCreatedFund(Fund $createdFund): self
    {
        if (!$this->createdFunds->contains($createdFund)) {
            $this->createdFunds[] = $createdFund;
            $createdFund->setAdmin($this);
        }

        return $this;
    }

    public function removeCreatedFund(Fund $createdFund): self
    {
        if ($this->createdFunds->removeElement($createdFund)) {
            // set the owning side to null (unless already changed)
            if ($createdFund->getAdmin() === $this) {
                $createdFund->setAdmin(null);
            }
        }

        return $this;
    }
}
