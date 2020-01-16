<?php

namespace NEOSoftWare\Deluge\Command;

use NEOSoftWare\Deluge\DelugeConsole;

/**
 * Class DefaultCommand
 * @package NEOSoftWare\Deluge\Command
 */
abstract class DefaultCommand
{
    /** @var DelugeConsole */
    protected $delugeConsole;

    public function __construct(DelugeConsole $delugeConsole)
    {
        $this->delugeConsole = $delugeConsole;
    }
}
