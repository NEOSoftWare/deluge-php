🌎 Deluge PHP
=====================

Php package from [deluge console](https://dev.deluge-torrent.org/wiki/UserGuide/ThinClient#Console)

Install
------------

Run `composer require neosoftware/deluge-php`


Use
-----
```php
$delugeConsole = new DelugeConsole([
    'host' => 'localhost',
    'user' => 'deluge',
    'password' => 'deluge',
]);

$torrents = (new InfoCommand($delugeConsole))->torrentList();

foreach($torrents as $torrent){
    print $torrent->name . ' '. $torrent->eta . PHP_EOL;
}
```
