<?php


namespace whereof\hprose\Support;


/**
 * Class Uris
 * @package whereof\hprose\Support
 */
class Uris
{
    /**
     * @param $tcp_uris
     * @return array
     */
    public static function getConfigLocalHost($tcp_uris)
    {
        $local = [];
        foreach ($tcp_uris as $url) {
            $urls    = parse_url($url);
            $local[] = $urls['scheme'] . '://127.0.0.1:' . $urls['port'];
        }
        return $local;
    }
}