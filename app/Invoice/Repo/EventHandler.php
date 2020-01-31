<?php
/**
 * EventHandler collect all properties from namespaces
 * */
namespace Invoice\Repo;

use Invoice\Interfaces\EventStruct;

class EventHandler implements EventStruct
{
    private $data = [];
    private $url = "http://www.mocky.io/v2/5e343bc23000008245d963bb";

    /**
     * Collect all data and prepare to sending.
     * @param string $classPath - class namespace under examples folder
     * */
    public function track(string $classPath): void
    {
        $res = new Reflector($classPath);

        // Check if memory get a limit, than send data and continue to work!
        if ($this->checkMemory()) {
            $this->flush();
        }
        $this->data = array_merge($this->data, $res->getProperties());
    }

    /**
     * Method checks if data are exists
     * then send to external URL and flush $data property to next call.
     * */
    public function flush(): void
    {
        if (count($this->data) > 0) {
            $ch = curl_init($this->url);

            $options = [
                CURLOPT_FORBID_REUSE => false,
                CURLOPT_FRESH_CONNECT => false,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($this->data),
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json'
                ]
            ];

            curl_setopt_array($ch, $options);
            $result = curl_exec($ch);

            // If result is true than close connection and clear data
            if ($result) {
                curl_close($ch);
                $this->data = null;
                $this->data = [];
            }
        }
    }

    /**
     * Check if memory get to limit
     * @return boolean
     * */
    private function checkMemory(): bool
    {
        $memUsage = memory_get_usage(true);
        $memoryLimit = ini_get('memory_limit');

        if (preg_match('/^(\d+)(.)$/', $memoryLimit, $matches)) {
            if ($matches[2] == 'M') { // If MB
                $memoryLimit = $matches[1] * 1024 * 1024;
            } else if ($matches[2] == 'K') { // If KB
                $memoryLimit = $matches[1] * 1024;
            }
        }

        return $memUsage >= $memoryLimit;
    }
}