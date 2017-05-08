<?php

namespace GeniusTS\PrayerTimes\Methods;


class UmQura extends Method
{

    /**
     * @var int
     */
    protected $duhrOffset = 0;

    /**
     * @var array<float>
     */
    protected $angles = [
        'fajr' => 18.5,
        'isha' => 0,
    ];

    /**
     * @var float
     */
    protected $interval = 90;

    /**
     * @var string
     */
    protected $name = 'um_qura';

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