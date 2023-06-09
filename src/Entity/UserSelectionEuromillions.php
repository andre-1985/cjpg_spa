<?php

namespace App\Entity;

use App\Repository\UserSelectionEuromillionsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserSelectionEuromillionsRepository::class)]
class UserSelectionEuromillions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'simple_array')]
    #[Assert\Count(
        max: 5,
        maxMessage: 'Vous avez sélectionner trop de numéros/boules, maximum 5 !',
    )]
    #[Assert\All([
        new Assert\Type(
            type: 'integer'
        ),
        new Assert\GreaterThanOrEqual(
            value: 1,
        ),
        new Assert\LessThanOrEqual(
            value: 50,
        )
    ])]
    public ?array $ballsSelectionEuromillions;

    #[ORM\Column(type: 'simple_array')]
    #[Assert\Count(
        max: 2,
        maxMessage: 'Vous avez sélectionner trop d\' étoiles, maximum 2 !',
    )]
    #[Assert\All([
        new Assert\Type(
            type: 'integer'
        ),
        new Assert\GreaterThanOrEqual(
            value: 1,
        ),
        new Assert\LessThanOrEqual(
            value: 12,
        )
    ])]
    public ?array $starsSelection;

    #[Assert\IsTrue(message: 'Vous devez sélectionner au moins 2 numéros !')]
    public function isValid(): bool
    {
        return count($this->ballsSelectionEuromillions) + count($this->starsSelection) >= 2;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBallsSelectionEuromillions(): ?array
    {
        return $this->ballsSelectionEuromillions;
    }

    public function setBallsSelectionEuromillions(?array $ballsSelectionEuromillions): void
    {
        $this->ballsSelectionEuromillions = $ballsSelectionEuromillions;
    }

    public function getStarsSelection(): ?array
    {
        return $this->starsSelection;
    }

    public function setStarsSelection(?array $starsSelection): void
    {
        $this->starsSelection = $starsSelection;
    }
}