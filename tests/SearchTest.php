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

    public function testBasicSearch()
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
}
