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
     * The Guzzle client used for communication with the nominatim server.
     *
     * @var Client
     */
    private $client;

    /**
     * The search object which serves as a template for new ones created
     * by `newSearch()` method.
     *
     * @var Search
     */
    private $baseSearch;

    public function __construct(Client $client, Search $baseSearch)
    {
        $this->client = $client;
        $this->baseSearch = $baseSearch;
    }

    /**
     * Factory method which creates a new instance of Nominatim.
     *
     * @param  string $url     Base URL of the nominatim server
     *                         (e.g. http://nominatim.openstreetmap.org/)
     * @param  Search $baseSearch The object to use as base when
     *                         creating new searches.
     *
     * @return Nominatim
     */
    public static function newInstance($url, Search $baseSearch = null)
    {
        $client = new Client([
            'base_uri' => $url
        ]);

        if (!isset($baseSearch)) {
            $baseSearch = new Search();
        }

        return new Nominatim($client, $baseSearch);
    }

    /**
     * Returns a new search object based on the base search.
     *
     * @return Search
     */
    public function newSearch()
    {
        return clone $this->baseSearch;
    }

    /**
     * Runs the query and returns the result set from Nominatim.
     *
     * @return array The decoded data returned from Nominatim.
     */
    public function find(Search $search)
    {
        $response = $this->client->get('search', [
            'query' => $search->getQuery()
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getClient()
    {
        return $this->client;
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
