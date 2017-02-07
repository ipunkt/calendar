<?php

namespace Ipunkt\Calendar\Ical\Parser;

class Factory
{
    /**
     * creates parser from url
     *
     * @param string $url
     * @return Parser
     */
    public static function createFromUrl(string $url): Parser
    {
        //	correct the protocol
        $url = str_replace('webcal://', 'http://', $url);

        return new Parser(fopen($url, 'r'));
    }

    /**
     * creates parser from string
     *
     * @param string $string
     * @return Parser
     */
    public static function createFromString(string $string): Parser
    {
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $string);
        rewind($stream);

        return new Parser($stream);
    }

    /**
     * creates parser from local file
     *
     * @param string $file
     * @return Parser
     */
    public static function createFromFile(string $file): Parser
    {
        return new Parser(fopen($file, 'r'));
    }
}