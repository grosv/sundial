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
        ];
    }

    public function esp(): array
    {
        return [
            'months' => [
                '1'       => [' enero '],
                '2'       => [' febrero ', ' feb '],
                '3'       => [' marzo ', ' mar '],
                '4'       => [' abril ', ' abr '],
                '5'       => [' mayo '],
                '6'       => [' junio ', ' jun '],
                '7'       => [' julio ', ' jul '],
                '8'       => [' agosto ',],
                '9'       => [' septiembre ', ' sept ', 'set'],
                '10'      => [' octubre ', ' oct '],
                '11'      => [' noviembre ', ' nov '],
                '12'      => [' diciembre ', ' dic '],
            ],
            'days' => [
                'sunday'    => [' domingo ', ' d ', ' do ', ' dom '],
                'monday'    => [' lunes ', ' lu ', ' lu ', ' lun '],
                'tuesday'   => [' martes ', ' m ', ' ma', ' mar '],
                'wednesday' => [' miércoles ', ' miercoles ', ' x ', ' mi ', ' mie ', ' mié '],
                'thursday'  => [' jueves ', ' j ', ' ju ', ' jue '],
                'friday'    => [' viernes ', ' v ', ' vi ', ' vie '],
                'saturday'  => [' sábado ', ' sabado ', ' s ', ' sá ', ' sa ', ' sab '],
            ],
        ];
    }
}
