<?php

namespace GeniusTS\PrayerTimes;


/**
 * Class Coordinates
 *
 * @package       GeniusTS\PrayerTimes
 *
 * @property-read float $longitude
 * @property-read float $latitude
 */
class Coordinates
{

    /**
     * @var float
     */
    protected $longitude;

    /**
     * @var float
     */
    private $latitude;

    /**
     * Coordinates constructor.
     *
     * @param float $longitude
     * @param float $latitude
     */
    public function __construct(float $longitude, float $latitude)
    {
        $this->longitude = $longitude;
        $this->latitude = $latitude;
    }

    /**
     * @param $attribute
     *
     * @return mixed
     */
    public function __get($attribute)
    {
        return $this->{$attribute};
    }
}