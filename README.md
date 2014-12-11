Opendi Nominatim
================

A simple interface to OSM Nominatim search.

[![Latest Stable Version](https://poser.pugx.org/opendi/nominatim/v/stable.png)](https://packagist.org/packages/opendi/nominatim) [![Total Downloads](https://poser.pugx.org/opendi/nominatim/downloads.png)](https://packagist.org/packages/opendi/nominatim) [![Build Status](https://circleci.com/gh/opendi/nominatim.png?circle-token=79b862406706bfb4020487aa98aa651a71a5cd4f)](https://circleci.com/gh/opendi/nominatim) [![License](https://poser.pugx.org/opendi/nominatim/license.png)](https://packagist.org/packages/opendi/nominatim)

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
$search = $nominatim->newSearch();
$search->query('1600 Pennsylvania Ave NW, Washington, DC 20500, United States');

$nominatim->find($search);
```

Or break it down by address:

```php
$search = $nominatim->newSearch()
    ->street('1600 Pennsylvania Ave NW')
    ->city('Washington')
    ->county('Washington')
    ->state('Washington DC')
    ->postalCode('DC 20500')
    ->country('United States')
    ->addressDetails();

$nominatim->find($search);
```

The result will be an array of results (in this case, only one result was
found).

```php
Array
(
    [0] => Array
        (
            [place_id] => 2632584431
            [licence] => Data © OpenStreetMap contributors, ODbL 1.0. http://www.openstreetmap.org/copyright
            [osm_type] => way
            [osm_id] => 238241022
            [boundingbox] => Array
                (
                    [0] => 38.8974898
                    [1] => 38.897911
                    [2] => -77.0368539
                    [3] => -77.0362521
                )

            [lat] => 38.8976989
            [lon] => -77.036553192281
            [display_name] => The White House, 1600, Pennsylvania Avenue Northwest, Thomas Circle, Southwest Waterfront, Washington, 20500, United States of America
            [class] => tourism
            [type] => attraction
            [importance] => 1.5576757387296
            [icon] => http://nominatim.openstreetmap.org/images/mapicons/poi_point_of_interest.p.20.png
            [address] => Array
                (
                    [attraction] => The White House
                    [house_number] => 1600
                    [pedestrian] => Pennsylvania Avenue Northwest
                    [neighbourhood] => Thomas Circle
                    [suburb] => Southwest Waterfront
                    [city] => Washington
                    [county] => Washington
                    [postcode] => 20500
                    [country] => United States of America
                    [country_code] => us
                )

        )

)
```
