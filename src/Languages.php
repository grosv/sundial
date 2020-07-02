<?php

declare(strict_types=1);

namespace Grosv\NaturalTime;

final class Languages
{
    public function load(string $language): array
    {
        if ($language === 'english') {
            return $this->english();
        }

        return $this->english();
    }

    public function english(): array
    {
        return [
            'months' => [
                'january'   => [' january ', ' jan '],
                'february'  => [' february ', ' feb '],
                'march'     => [' march ', ' mar '],
                'april'     => [' april ', ' apr '],
                'may'       => [' may '],
                'june'      => [' june ', ' jun '],
                'july'      => [' july ', ' jul '],
                'august'    => [' august ', ' aug '],
                'september' => [' september ', ' sep '],
                'october'   => [' october ', ' oct '],
                'november'  => [' november ', ' nov '],
                'december'  => [' december ', ' dec '],
            ],
        ];
    }
}
