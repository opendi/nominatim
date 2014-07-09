<?php

namespace Opendi\Nominatim\Tests;

use Mockery as m;

use Opendi\Nominatim\Nominatim;
use Opendi\Nominatim\Search;

class SearchTest extends \PHPUnit_Framework_TestCase
{
    protected $url = "http://nominatim.openstreetmap.org/";

    protected function setUp()
    {
        $this->nominatim = Nominatim::newInstance($this->url);
    }

    protected function tearDown()
    {
        m::close();
    }

    public function testQuery()
    {
        $search = $this->nominatim->newSearch()
            ->query('foo');

        $expected = [
            'format' => 'json',
            'q' => 'foo'
        ];

        $query = $search->getQuery();
        $this->assertSame($expected, $query);

        $expected = http_build_query($query);
        $this->assertSame($expected, $search->getQueryString());
    }

    public function testAddress()
    {
        $search = $this->nominatim->newSearch()
            ->street('1600 Pennsylvania Ave NW')
            ->city('Washington')
            ->county('Washington')
            ->state('Washington DC')
            ->postalCode('DC 20500')
            ->country('United States');

        $expected = [
            'format' => 'json',
            'street' => '1600 Pennsylvania Ave NW',
            'city' => 'Washington',
            'county' => 'Washington',
            'state' => 'Washington DC',
            'postalcode' => 'DC 20500',
            'country' => 'United States',
        ];

        $query = $search->getQuery();
        $this->assertSame($expected, $query);

        $expected = http_build_query($query);
        $this->assertSame($expected, $search->getQueryString());
    }

    public function testOthers()
    {
        $search = $this->nominatim->newSearch()
            ->query('Zagreb')
            ->addressDetails()
            ->countryCode('hr')
            ->countryCode('en')
            ->language('hr');

        $expected = [
            'format' => 'json',
            'q' => 'Zagreb',
            'addressdetails' => '1',
            'countrycode' => 'hr,en',
            'accept-language' => 'hr',
        ];

        $query = $search->getQuery();
        $this->assertSame($expected, $query);

        $expected = http_build_query($query);
        $this->assertSame($expected, $search->getQueryString());
    }

    public function testFind()
    {
        $mockData = ['foo' => 'bar'];

        $mockResponse = m::mock('GuzzleHttp\Message\Response');
        $mockResponse
            ->shouldReceive('json')
            ->once()
            ->andReturn($mockData);

        $guzzle = m::mock("GuzzleHttp\\Client");
        $guzzle
            ->shouldReceive('get')
            ->once()
            ->with('search', ['query'=>['format'=>'json','q'=>'foo']])
            ->andReturn($mockResponse);

        $nominatim = new Nominatim(new Search($guzzle));

        $search = $nominatim->newSearch()->query('foo');
        $queryString = $search->getQueryString();

        $this->assertSame($mockData, $search->find());
    }
}
