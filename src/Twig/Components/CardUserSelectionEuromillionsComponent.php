<?php

namespace App\Twig\Components;

use App\Entity\UserSelectionEuromillions;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('card_user_selection_euromillions')]
final class CardUserSelectionEuromillionsComponent
{
    public UserSelectionEuromillions $userSelection;
}
