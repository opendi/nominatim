<?php

namespace Opendi\Nominatim;

use GuzzleHttp\Client;

/**
 * Searches a OSM nominatim service for places.
 *
 * @see http://wiki.openstreetmap.org/wiki/Nominatim
 */
class Search
{
    /**
     * The HTTP query as an array.
     * @var array
     */
    private $query = [];

    public function __construct(array $query = [])
    {
        $this->query = $query;

        // Hardcode format to JSON because we depend on it for decoding
        $this->query['format'] = 'json';
    }

    // -- Builder methods ------------------------------------------------------

    /**
     * Query string to search for.
     *
     * @return NominatimSearch
     */
    public function query($query)
    {
        $this->query['q'] = $query;

        return $this;
    }

    /**
     * Street to search for (experimental).
     *
     * Do not combine with query().
     *
     * @return NominatimSearch
     */
    public function street($street)
    {
        $this->query['street'] = $street;

        return $this;
    }

    /**
     * City to search for (experimental).
     *
     * Do not combine with query().
     *
     * @return NominatimSearch
     */
    public function city($city)
    {
        $this->query['city'] = $city;

        return $this;
    }

    /**
     * County to search for (experimental).
     *
     * Do not combine with query().
     *
     * @return NominatimSearch
     */
    public function county($county)
    {
        $this->query['county'] = $county;

        return $this;
    }

    /**
     * State to search for (experimental).
     *
     * Do not combine with query().
     *
     * @return NominatimSearch
     */
    public function state($state)
    {
        $this->query['state'] = $state;

        return $this;
    }

    /**
     * Country to search for (experimental).
     *
     * Do not combine with query().
     *
     * @return NominatimSearch
     */
    public function country($country)
    {
        $this->query['country'] = $country;

        return $this;
    }

    /**
     * Postal code to search for (experimental).
     *
     * Do not combine with query().
     *
     * @return NominatimSearch
     */
    public function postalCode($postalCode)
    {
        $this->query['postalcode'] = $postalCode;

        return $this;
    }

    /**
     * Preferred language order for showing search results, overrides the value
     * specified in the "Accept-Language" HTTP header. Either uses standard
     * rfc2616 accept-language string or a simple comma separated list of
     * language codes.
     *
     * @return NominatimSearch
     */
    public function language($language)
    {
        $this->query['accept-language'] = $language;

        return $this;
    }

    /**
     * Limit search results to a specific country (or a list of countries).
     *
     * <countrycode> should be the ISO 3166-1alpha2 code, e.g. gb for the United
     * Kingdom, de for Germany, etc.
     *
     * @return NominatimSearch
     */
    public function countryCode($countrycode)
    {
        if (!preg_match('/^[a-z]{2}$/i', $countrycode)) {
            throw new \InvalidArgumentException("Invalid country code: \"$countrycode\"");
        }

        if (!isset($this->query['countrycode'])) {
            $this->query['countrycode'] = $countrycode;
        } else {
            $this->query['countrycode'] .= "," . $countrycode;
        }

        return $this;
    }

    /**
     * Include a breakdown of the address into elements.
     *
     * @param  boolean $details
     * @return NominatimSearch
     */
    public function addressDetails($details = true)
    {
        $this->query['addressdetails'] = $details ? "1" : "0";

        return $this;
    }

    // -- Accessors ------------------------------------------------------------

    /**
     * Returns the query as an array.
     *
     * @return array
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Returns the URL-encoded query.
     *
     * @return string
     */
    public function getQueryString()
    {
        return http_build_query($this->query);
    }
}
