<?php

namespace MusicBox\Service;

/**
 * Provides a way to retrieve the Soundcloud oEmbed code.
 */
class SoundCloud
{
    /**
     * Returns the oEmbed code for an artist with the provided url.
     *
     * @param string $url The soundcloud url of the artist.
     *
     * @return string The oEmbed code.
     */
    public function getWidget($url)
    {
        $url = 'http://soundcloud.com/oembed?format=json&url=' . urlencode($url);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        $return = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($return, TRUE);
        return $data['html'];
    }
}
