<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{

    public function getFilters(): array
    {
        return [
            new TwigFilter('getTotal', [$this, 'getTotal']),
        ];
    }

    // fonction permettant de récupérer le nombre total de produits contenus dans le panier dans le menu
    public function getTotal($array): int
    {
        $total = array_sum($array);

        return $total;

    }

}