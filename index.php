<?php
require_once "vendor/autoload.php";

use Invoice\Repo\EventHandler;

$event = new EventHandler;

// Dynamic examples folder scan
$dirFiles = scandir("app/Invoice/Examples");
$classes = [];
foreach ($dirFiles as $file) {
    if (!is_dir($file)) {
        $classes[] = 'Invoice\\Examples\\' . pathinfo($file)['filename'];
    }
}

for ($i = 0, $count = 1; $i < count($classes); $i++, $count++) {
    $event->track($classes[$i]);
    if ($count === count($classes)) $event->flush();
}