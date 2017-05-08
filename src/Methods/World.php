<?php
/**
 * Created by PhpStorm.
 * User: aboudeh
 * Date: 02/05/2017
 * Time: 16:02
 */

namespace GeniusTS\PrayerTimes\Methods;


class World extends Method
{

    /**
     * @var array<float>
     */
    protected $angles = [
        'fajr' => 18.0,
        'isha' => 17.0,
    ];

    /**
     * @var float
     */
    protected $interval = 0;

    /**
     * @var string
     */
    protected $name = 'muslim_world_league';

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