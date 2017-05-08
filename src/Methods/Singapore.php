<?php

namespace GeniusTS\PrayerTimes\Methods;


class Singapore extends Method
{

    /**
     * @var array<float>
     */
    protected $angles = [
        'fajr' => 20,
        'isha' => 18,
    ];

    /**
     * @var float
     */
    protected $interval = 0;

    /**
     * @var string
     */
    protected $name = 'majlis_ugama_islam_singapura';

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