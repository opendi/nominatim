<?php

namespace Opendi\Nominatim\Tests;

use Mockery as m;

use Opendi\Nominatim\Nominatim;
use Opendi\Nominatim\Search;

class NominatimTest extends \PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function testNominatimFactory()
    {
        $guzzle = m::mock("GuzzleHttp\\Client");
        $baseSearch = new Search($guzzle);
        $instance = new Nominatim($baseSearch);

        $this->assertInstanceOf(Nominatim::class, $instance);
        $this->assertInstanceOf(Search::class, $instance->newSearch());
        $this->assertSame($guzzle, $instance->newSearch()->getClient());
    }
}