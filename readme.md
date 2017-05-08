# Prayer times

A PHP library to calculate prayer times.

This project was inspired from [batoulapps/Adhan](https://github.com/batoulapps/Adhan) package.

## Installation

```bash
	composer require geniusts/prayer-times
```

## Usage

```php
    use \GeniusTS\PrayerTimes\Prayer;
    use \GeniusTS\PrayerTimes\Coordinates;

    $prayer = new Prayer(new Coordinates($longitude, $latitude));
    // Or
    $prayer = new Prayer();
    $prayer->setCoordinates($longitude, $latitude);

    // Return an \GeniusTS\PrayerTimes\Times instance
    $times = $prayer->times('2017-5-9');
    $times->setTimeZone(+3);

    echo $times->fajr->format('h:i a');
```


## Configurations

* Change the calculation method.

```php
    // change instance value
    use \GeniusTS\PrayerTimes\Methods\World;

    $prayer->setMethod(new World);
    // Or
    $prayer->setMethod(Prayer::METHOD_MUSLIM_WORLD_LEAGUE);

    // change default value
    Prayer::setDefaultMethod(new World);
    // Or
    Prayer::setDefaultMethod(Prayer::METHOD_MUSLIM_WORLD_LEAGUE);
```

* Changing the mathhad of calculation Asr prayer.

```php
    //Change instance value
    $prayer->setMathhab(Prayer::MATHHAB_HANAFI);


    //Change default value
    Prayer::setDefaultMathhab(Prayer::MATHHAB_HANAFI);
```

* Changing the high latitude rule.

```php
    //Change instance value
    $prayer->setHighLatitudeRule(Prayer::HIGH_LATITUDE_MIDDLE_OF_NIGHT);


    //Change default value
    Prayer::setDefaultHighLatitudeRule(Prayer::HIGH_LATITUDE_MIDDLE_OF_NIGHT);
```

* Changing times adjustments.

```php
    //Change instance value
    $prayer->setAdjustments(0, 2, 5, 3, 8, 4);


    //Change default value
    Prayer::setDefaultAdjustments(0, 2, 5, 3, 8, 4);
```


## License

This package is free software distributed under the terms of the MIT license.
