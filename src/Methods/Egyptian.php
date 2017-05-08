<?php

namespace GeniusTS\PrayerTimes\Methods;


class Egyptian extends Method
{

    /**
     * @var array<float>
     */
    protected $angles = [
        'fajr' => 19.5,
        'isha' => 17.5,
    ];

    /**
     * @var float
     */
    protected $interval = 0;

    /**
     * @var string
     */
    protected $name = 'egyptian_general_authority';

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