<?php

namespace App\Entity;

use App\Repository\UserSelectionLotoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserSelectionLotoRepository::class)]
class UserSelectionLoto
{
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
            value: 49,
        )
    ])]
    public ?array $ballsSelectionLoto;
    #[ORM\Column(type: 'simple_array')]
    #[Assert\Count(
        max: 1,
        maxMessage: 'Vous avez sélectionner trop de numéros chance, maximum 1 !',
    )]
    #[Assert\All([
        new Assert\Type(
            type: 'integer'
        ),
        new Assert\GreaterThanOrEqual(
            value: 1,
        ),
        new Assert\LessThanOrEqual(
            value: 10,
        )
    ])]
    public ?array $luckyNumberSelection;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\IsTrue(message: 'Vous devez sélectionner au moins 2 numéros !')]
    public function isValid(): bool
    {
        return count($this->ballsSelectionLoto) + count($this->luckyNumberSelection) >= 2;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBallsSelectionLoto(): ?array
    {
        return $this->ballsSelectionLoto;
    }

    public function setBallsSelectionLoto(?array $ballsSelectionLoto): void
    {
        $this->ballsSelectionLoto = $ballsSelectionLoto;
    }

    public function getLuckyNumberSelection(): ?array
    {
        return $this->luckyNumberSelection;
    }

    public function setLuckyNumberSelection(?array $luckyNumberSelection): void
    {
        $this->luckyNumberSelection = $luckyNumberSelection;
    }
}
