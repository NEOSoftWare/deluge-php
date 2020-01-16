<?php

namespace NEOSoftWare\Deluge;

/**
 * Class Helper
 * @package NEOSoftWare\Deluge
 */
class Helper
{
    /**
     * @param string $from
     *
     * @return int|null
     */
    public static function convertToBytes(string $from): ?int
    {
        $units = ['Byte', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];

        $data = explode(' ', $from);

        $number = (float) $data[0] ?? 0;
        $suffix = $data[1] ?? null;

        $exponent = array_flip($units)[$suffix] ?? null;
        if ($exponent === null) {
            return null;
        }

        return $number * (1024 ** $exponent);
    }
}
