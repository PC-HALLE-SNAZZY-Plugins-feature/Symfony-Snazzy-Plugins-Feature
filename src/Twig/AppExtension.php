<?php

// src/Twig/AppExtension.php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('short_number', [$this, 'shortNumberFilter']),
        ];
    }

    public function shortNumberFilter($number)
    {
        if ($number >= 1000 && $number < 1000000) {
            return round($number / 1000, 1) . 'k';
        } elseif ($number >= 1000000 && $number < 1000000000) {
            return round($number / 1000000, 1) . 'M';
        } elseif ($number >= 1000000000) {
            return round($number / 1000000000, 1) . 'B';
        }

        return $number;
    }
}
