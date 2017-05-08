<?php

namespace GeniusTS\PrayerTimes\Methods;


class Gulf extends Method
{

    /**
     * @var int
     */
    protected $duhrOffset = 0;

    /**
     * @var array<float>
     */
    protected $angles = [
        'fajr' => 19.5,
        'isha' => 0,
    ];

    /**
     * @var float
     */
    protected $interval = 90;

    /**
     * @var string
     */
    protected $name = 'gulf_region';

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
     * @return int
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