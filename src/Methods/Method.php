<?php

namespace GeniusTS\PrayerTimes\Methods;


/**
 * Class Method
 *
 * @package GeniusTS\PrayerTimes
 */
abstract class Method
{

    /**
     * Default behavior waits 1 minute for the
     * sun to pass the zenith and dhuhr to enter
     *
     * @var int
     */
    protected $duhrOffset = 1;

    /**
     * Default behavior don't add minutes to
     * sunset time to account for light refraction
     *
     * @var int
     */
    protected $maghribOffset = 1;

    /**
     * Get fajr angle
     *
     * @return float
     */
    abstract public function fajrAngle(): float;

    /**
     * Get isha angle
     *
     * @return float
     */
    abstract public function ishaAngle(): float;

    /**
     * Get Isha interval
     *
     * @return mixed
     */
    abstract public function ishaInterval(): int;

    /**
     * get Duhr offset
     *
     * @return int
     */
    public function duhrOffset(): int
    {
        return $this->duhrOffset;
    }

    /**
     * get Maghrib offset
     *
     * @return int
     */
    public function maghribOffset(): int
    {
        return $this->maghribOffset;
    }

    /**
     * Get method name
     *
     * @return mixed
     */
    abstract public function name();
}