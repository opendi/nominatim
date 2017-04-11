<?php

namespace Opendi\Nominatim\Tests;

use Mockery as m;

use Opendi\Nominatim\Nominatim;
use Opendi\Nominatim\Search;
use PHPUnit\Framework\TestCase;

class NominatimTest extends TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function testNominatimFactory()
    {
        $guzzle = m::mock("GuzzleHttp\\Client");
        $baseSearch = new Search();
        $baseSearch->city("Zagreb");
        $instance = new Nominatim($guzzle, $baseSearch);
        $search = $instance->newSearch();

        $this->assertInstanceOf(Nominatim::class, $instance);
        $this->assertInstanceOf(Search::class, $search);
        $this->assertEquals($baseSearch, $search);
        $this->assertNotSame($baseSearch, $search);
        $this->assertSame($guzzle, $instance->getClient());
    }

    public function testDefaults()
    {
        $n = Nominatim::newInstance("http://nominatim.openstreetmap.org/");
        $n->city("Zagreb");

        $s = $n->newSearch();

        $expected = [
            "format" => "json",
            "city" => "Zagreb",
        ];

        $this->assertSame($expected, $s->getQuery());
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage foo() does not exist
     */
    public function testInvalidMethod()
    {
        $n = Nominatim::newInstance("http://nominatim.openstreetmap.org/");
        $n->foo();
    }

    public function testFind()
    {
        $mockData = '{"foo": "bar"}';

        $mockResponse = m::mock('GuzzleHttp\Message\Response');
        $mockResponse
            ->shouldReceive('getBody')
            ->once()
            ->andReturn($mockData);

        $guzzle = m::mock("GuzzleHttp\\Client");
        $guzzle
            ->shouldReceive('get')
            ->once()
            ->with('search', ['query'=>['format'=>'json','q'=>'foo']])
            ->andReturn($mockResponse);

        $nominatim = new Nominatim($guzzle, new Search());

        $search = $nominatim->newSearch()->query('foo');
        $queryString = $search->getQueryString();

        $this->assertSame(json_decode($mockData, true), $nominatim->find($search));
    }
}
