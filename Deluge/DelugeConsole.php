<?php

namespace NEOSoftWare\Deluge;

/**
 * Class DelugeConsole
 * @package NEOSoftWare\Deluge
 */
class DelugeConsole
{
    /** @var string */
    protected $consoleCommand;

    /** @var string */
    protected $host;

    /** @var int */
    protected $port;

    /** @var string|null */
    protected $user;

    /** @var string|null */
    protected $password;

    /**
     * DelugeConsole constructor.
     *
     * @param $config
     */
    public function __construct($config)
    {
        $this->consoleCommand = $config['console_command'] ?? 'deluge-console';
        $this->host = $config['host'] ?? 'localhost';
        $this->port = $config['port'] ?? 58846;
        $this->user = $config['user'] ?? '';
        $this->password = $config['password'] ?? '';
    }

    /**
     * @param $command
     *
     * @return string|null
     * @throws null
     */
    public function command($command)
    {
        $command = str_replace('"', '', $command);

        $line = shell_exec("{$this->consoleCommand} \" connect {$this->host}:{$this->port} {$this->user} {$this->password}; $command; exit;\"");

        if (strpos($line, 'Failed to connect') !== false){
            throw new ExceptionDeluge($line);
        }

        return $line;
    }
}
