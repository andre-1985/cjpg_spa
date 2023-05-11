<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('user_loto_results')]
final class UserLotoResultsComponent
{
    use DefaultActionTrait;
}
