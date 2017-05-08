<?php

namespace GeniusTS\PrayerTimes\Methods;


class Kuwait extends Method
{

    /**
     * @var array<float>
     */
    protected $angles = [
        'fajr' => 18,
        'isha' => 17.5,
    ];

    /**
     * @var float
     */
    protected $interval = 0;

    /**
     * @var string
     */
    protected $name = 'kuwait';

    /**
     * Get fajr angle
     *
     * @return float
     */
    public function fajrAngle(): float
    {
        return $this->angles['fajr'];
    }

    /**
     * Get isha angle
     *
     * @return float
     */
    public function ishaAngle(): float
    {
        return $this->angles['isha'];
    }

    /**
     * Get Isha interval
     *
     * @return mixed
     */
    public function ishaInterval(): int
    {
        return $this->interval;
    }

    /**
     * Get method name
     *
     * @return mixed
     */
    public function name()
    {
        return $this->name;
    }
}