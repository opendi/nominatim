Opendi Nominatim
================

A simple interface to OSM Nominatim search.

See [Nominatim documentation](http://wiki.openstreetmap.org/wiki/Nominatim) for
info on the service.

Basic usage
-----------

Create a new instance of Nominatim by using the `newInstance()` factory method.

```php
use Opendi\Nominatim\Nominatim;

$url = "http://nominatim.openstreetmap.org/";
$nominatim = Nominatim::newInstance($url);
```

Searching by query:

```php
$result = $nominatim->newSearch()
    ->query('1600 Pennsylvania Ave NW, Washington, DC 20500, United States')
    ->find();
```

Or break it down by address:

```
$result = $nominatim->newSearch()
    ->street('1600 Pennsylvania Ave NW')
    ->city('Washington')
    ->postalCode('DC 20500')
    ->country('United States')
    ->find();
```

The result will be an array of results (in this case, only one result was
found).

```php
array (
  array (
    'place_id' => '9141060761',
    'licence' => 'Data © OpenStreetMap contributors, ODbL 1.0. http://www.openstreetmap.org/copyright',
    'osm_type' => 'way',
    'osm_id' => '238241022',
    'boundingbox' =>
    array (
      '38.8974876403809',
      '38.8979110717773',
      '-77.0368576049805',
      '-77.036247253418',
    ),
    'lat' => '38.8976989',
    'lon' => '-77.036553192281',
    'display_name' => 'The White House, 1600, Pennsylvania Avenue Northwest, Farragut Square, Southwest Waterfront, Washington, 20500, United States of America',
    'class' => 'tourism',
    'type' => 'attraction',
    'importance' => 1.3376757387296001,
    'icon' => 'http://nominatim.openstreetmap.org/images/mapicons/poi_point_of_interest.p.20.png',
  ),
)
```
