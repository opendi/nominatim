<?php

namespace Opendi\Nominatim;

use GuzzleHttp\Client;

/**
 * Searches a OSM nominatim service for places.
 *
 * @see http://wiki.openstreetmap.org/wiki/Nominatim
 */
class Nominatim
{
    /**
     * The Guzzle client used for comunnication with the nominatim server.
     *
     * @var GuzzleHttp\Client
     */
    private $guzzle;

    /**
     * The search object which serves as a template for new ones created
     * by `newSearch()` method.
     *
     * @var Search
     */
    private $baseSearch;

    public function __construct(Search $baseSearch)
    {
        $this->baseSearch = $baseSearch;
    }

    // -- Factory methods ------------------------------------------------------

    /**
     * Factory method which creates a new instance of Nominatim.
     *
     * @param  string $url     Base URL of the nominatim server
     *                         (e.g. http://nominatim.openstreetmap.org/)
     * @param  Search $baseSearch The object to use as base when
     *                         creating new searches.
     * @return Nominatim
     */
    public static function newInstance($url, Search $baseSearch = null)
    {
        $guzzle = new Client([
            'base_url' => $url
        ]);

        if (!isset($baseSearch)) {
            $baseSearch = new Search($guzzle);
        }

        return new Nominatim($baseSearch);
    }

    /**
     * Returns a new search object.
     *
     * @return Search
     */
    public function newSearch()
    {
        return clone $this->baseSearch;
    }

    /**
     * Forward all calls to Search methods to underlying base search
     * object.
     */
    public function __call($name, $params)
    {
        if (method_exists($this->baseSearch, $name)) {
            call_user_func_array([$this->baseSearch, $name], $params);
            return $this;
        } else {
            $class = __CLASS__;
            throw new \BadMethodCallException("Method $class::$name() does not exist.");
        }
    }
}

