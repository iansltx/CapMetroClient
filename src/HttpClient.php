<?php

namespace iansltx\CapMetroClient;

class HttpClient
{
    public function getJSONAsObject($url) {
        $contents = file_get_contents($url);
        if ($contents === false)
            throw new \RuntimeException('Contents could not be fetched');

        $decoded = json_decode($contents);
        if ($decoded === false)
            throw new \RuntimeException('Contents could not be decoded', json_last_error());

        return $decoded;
    }
}
