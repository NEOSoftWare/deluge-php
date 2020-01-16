<?php

namespace NEOSoftWare\Deluge\Command;

use NEOSoftWare\Deluge\Helper;
use NEOSoftWare\Deluge\Type\Torrent;
use function Psy\debug;

/**
 * Class InfoCommand
 * @package NEOSoftWare\Deluge\Command
 */
class InfoCommand extends DefaultCommand
{
    /**
     * @return Torrent[]
     */
    public function torrentList()
    {
        $content = $this->delugeConsole->command('info -v');
        $torrents = [];
        $lines = explode("\n \n", $content);

        foreach ($lines ?? [] as $lineTorrent){
            $torrents[] = Torrent::fromData($this->parseTorrent($lineTorrent));
        }

        return $torrents;
    }

    /**
     * @param string $torrentId
     *
     * @return Torrent
     */
    public function torrent(string $torrentId)
    {
        $content = $this->delugeConsole->command('info -v ' . $torrentId);

        return Torrent::fromData($this->parseTorrent($content));
    }

    /**
     * @param $pattern
     * @param $content
     *
     * @return mixed|null
     */
    protected function parseLine($pattern, $content)
    {
        $pattern = str_replace('/', '\/', $pattern);

        preg_match('/' . $pattern . '/iums', $content, $row);

        return $row[1] ?? null;
    }

    /**
     * @param $content
     *
     * @return array
     */
    protected function parseTorrent($content)
    {
        $torrentData = [];

        $torrentData['name'] = $this->parseLine('Name: ([^\n]+)', $content);
        $torrentData['torrent_id'] = $this->parseLine('ID: ([^\n]+)', $content);
        $torrentData['state'] = $this->parseLine('State: (\w+)', $content);
        $torrentData['eta'] = $this->parseLine('State: .*?ETA: ([^\n]+)', $content);
        $torrentData['size'] = Helper::convertToBytes($this->parseLine('Size: .*?/([0-9\.]+ [\w]+)', $content));
        $torrentData['down_speed'] = $this->parseLine('State: .*?Down Speed: ([0-9\.]+ [\w/]+)', $content);
        $torrentData['progress'] = (float)$this->parseLine('Progress: ([0-9\.]+)%', $content);

        $contentFiles = $this->parseLine('::Files\n(.*?)\n  ::', $content) ?? '';

        $files = [];
        foreach (explode("\n", $contentFiles) ?? [] as $contentFile){
            $files[] = [
                'file' => $this->parseLine('([^ ]+) ', $contentFile),
                'size' => Helper::convertToBytes($this->parseLine('\(([0-9\.]+ [\w]+)\)', $contentFile)),
                'progress' => (float)$this->parseLine('Progress: ([0-9\.]+)%', $contentFile),
            ];
        }

        $torrentData['files'] = $files;

        return $torrentData;
    }
}
