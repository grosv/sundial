<?php

declare(strict_types=1);

namespace Grosv\Sundial;

final class Languages
{
    public function load(string $language): array
    {
        if ($language === 'eng') {
            return $this->eng();
        }

        return $this->eng();
    }

    public function eng(): array
    {
        return [
            'months' => [
                '1'       => [' january ', ' jan '],
                '2'       => [' february ', ' feb '],
                '3'       => [' march ', ' mar '],
                '4'       => [' april ', ' apr '],
                '5'       => [' may '],
                '6'       => [' june ', ' jun '],
                '7'       => [' july ', ' jul '],
                '8'       => [' august ', ' aug '],
                '9'       => [' september ', ' sep '],
                '10'      => [' october ', ' oct '],
                '11'      => [' november ', ' nov '],
                '12'      => [' december ', ' dec '],
            ],
            'days' => [
                'sunday'    => [' sunday ', ' sun '],
                'monday'    => [' monday ', ' mon '],
                'tuesday'   => [' tuesday ', ' tue '],
                'wednesday' => [' wednesday ', ' wed '],
                'thursday'  => [' thursday ', ' thu '],
                'friday'    => [' friday ', ' fri '],
                'saturday'  => [' saturday ', ' sat '],
            ],
            'at' => [
                'at', 'starting', 'beginning',
            ],
            'on' => [
                'on',
            ],
        ];
    }
}
