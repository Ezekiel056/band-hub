<?php

// src/Service/YoutubeLinksResolver.php
namespace App\Service;

class YoutubeLinksResolver
{
    public function resolve(string $url): ?string
    {
        preg_match('/(?:youtu\.be\/|(?:www\.)?youtube\.com\/(?:watch\?(?:[^#]*&)?v=|embed\/))([a-zA-Z0-9_-]{11})/', $url, $matches);

        if (!isset($matches[1])) {
            return null;
        }

        $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];

        $parsed = parse_url($url);
        if (isset($parsed['query'])) {
            parse_str($parsed['query'], $params);
            if (isset($params['si'])) {
                $embedUrl .= '?si=' . $params['si'];
            }
        }

        return $embedUrl;
    }
}
