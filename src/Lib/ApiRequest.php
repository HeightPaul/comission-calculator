<?php

namespace ComissionCli\Lib;

use ComissionCli\Exception\HttpNotFoundException;

/**
 * @brief Api Request class as a wrapper
 */
class ApiRequest
{
    public function getJson(string $url, ?string $header = null): array
    {
        $rawResult = $this->getRawData($url, $header);
        if(!$rawResult) {
            throw new HttpNotFoundException();
        }
        return json_decode($rawResult, true);
    }

    /**
     * string|false
     */
    public function getRawData(string $url, ?string $header = null)
    {
        if(isset($header)) {
            $options = [
                'http' => [
                    'header' => $header
                ]
            ];
            $context = stream_context_create($options);
        }
        return file_get_contents($url, false, $context ?? null);
    }
}
