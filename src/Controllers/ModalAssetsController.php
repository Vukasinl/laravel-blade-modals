<?php

namespace Vukasinl\Modals\Controllers;

class ModalAssetsController
{
    public function script()
    {
        return $this->pretendResponseIsFile(__DIR__ . '/../../dist/js/modals.js');
    }

    public function maps()
    {
        return $this->pretendResponseIsFile(__DIR__ . '/../../dist/js/modals.js.map');
    }

    public function style()
    {
        return $this->pretendResponseIsFile(__DIR__ . '/../../dist/css/modals.css');
    }


    protected function pretendResponseIsFile($file, $mimeType = 'application/javascript')
    {
        $expires = strtotime('+1 year');
        $lastModified = filemtime($file);
        $cacheControl = 'public, max-age=31536000';

        if ($this->matchesCache($lastModified)) {
            return response()->make('', 304, [
                'Expires' => $this->httpDate($expires),
                'Cache-Control' => $cacheControl,
            ]);
        }

        return response()->file($file, [
            'Content-Type' => "$mimeType; charset=utf-8",
            'Expires' => $this->httpDate($expires),
            'Cache-Control' => $cacheControl,
            'Last-Modified' => $this->httpDate($lastModified),
        ]);
    }

    protected function matchesCache($lastModified)
    {
        $ifModifiedSince = $_SERVER['HTTP_IF_MODIFIED_SINCE'] ?? '';

        return @strtotime($ifModifiedSince) === $lastModified;
    }

    protected function httpDate($timestamp)
    {
        return sprintf('%s GMT', gmdate('D, d M Y H:i:s', $timestamp));
    }
}
