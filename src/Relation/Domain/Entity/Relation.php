<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Relation\Domain\Entity;

use App\Entity\Currency;
use App\Repository\RelationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RelationRepository::class)]
class Relation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 200)]
    private string $name;

    #[ORM\Column(length: 100)]
    private string $shortName;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $cocNumber = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $remarks = null;

    #[ORM\OneToOne(inversedBy: 'relation', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Currency $currency = null;

    #[ORM\OneToMany(mappedBy: 'relation', targetEntity: RelationContact::class)]
    private Collection $contacts;

    #[ORM\OneToMany(mappedBy: 'relation', targetEntity: RelationAddress::class)]
    private Collection $addresses;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
        $this->addresses = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getShortName(): string
    {
        return $this->shortName;
    }

    public function setShortName(string $shortName): self
    {
        $this->shortName = $shortName;

        return $this;
    }

    public function getCocNumber(): ?string
    {
        return $this->cocNumber;
    }

    public function setCocNumber(?string $cocNumber): self
    {
        $this->cocNumber = $cocNumber;

        return $this;
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

    public function getRemarks(): ?string
    {
        return $this->remarks;
    }

    public function setRemarks(?string $remarks): self
    {
        $this->remarks = $remarks;

        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(RelationContact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts->add($contact);
            $contact->setRelation($this);
        }

        return $this;
    }

    public function removeContact(RelationContact $contact): self
    {
        if ($this->contacts->removeElement($contact)) {
            if ($contact->getRelation() === $this) {
                $contact->setRelation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Address>
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses->add($address);
            $address->setRelation($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->addresses->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getRelation() === $this) {
                $address->setRelation(null);
            }
        }

        return $this;
    }
}
