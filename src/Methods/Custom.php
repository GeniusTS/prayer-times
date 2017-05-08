<?php

namespace GeniusTS\PrayerTimes\Methods;


class Custom extends Method
{

    /**
     * @var array<float>
     */
    protected $angles = [
        'fajr' => 0,
        'isha' => 0,
    ];

    /**
     * @var float
     */
    protected $interval = 0;

    /**
     * @var string
     */
    protected $name = 'custom';

    /**
     * Custom constructor.
     *
     * @param float $fajrAngle
     * @param float $ishaAngle
     * @param float $interval
     * @param int   $duhrOffset
     * @param int   $maghribOffset
     */
    public function __construct(
        float $fajrAngle = 0,
        float $ishaAngle = 0,
        float $interval = 0,
        int $duhrOffset = 1,
        int $maghribOffset = 1
    )
    {
        $this->angles['fajr'] = $fajrAngle;
        $this->angles['isha'] = $ishaAngle;
        $this->interval = $interval;
        $this->duhrOffset = $duhrOffset;
        $this->maghribOffset = $maghribOffset;
    }

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

    /**
     * Set Fajr angle
     *
     * @param float $value
     *
     * @return $this
     */
    public function setFajrAngle(float $value)
    {
        $this->angles['fajr'] = $value;

        return $this;
    }

    /**
     * Set Isha angle
     *
     * @param float $value
     *
     * @return $this
     */
    public function setIshaAngle(float $value)
    {
        $this->angles['isha'] = $value;

        return $this;
    }

    /**
     * Set Isha inteval
     *
     * @param int $value
     *
     * @return $this
     */
    public function setInterval(int $value)
    {
        $this->interval = $value;

        return $this;
    }

    /**
     * @param int $duhrOffset
     *
     * @return $this
     */
    public function setDuhrOffset(int $duhrOffset)
    {
        $this->duhrOffset = $duhrOffset;

        return $this;
    }

    /**
     * @param int $maghribOffset
     *
     * @return $this
     */
    public function setMaghribOffset(int $maghribOffset)
    {
        $this->maghribOffset = $maghribOffset;

        return $this;
    }

}