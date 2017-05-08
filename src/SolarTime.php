<?php
/**
 * Created by PhpStorm.
 * User: aboudeh
 * Date: 02/05/2017
 * Time: 17:50
 */

namespace GeniusTS\PrayerTimes;


use Carbon\Carbon;

/**
 * Class SolarTime
 *
 * @package GeniusTS\PrayerTimes
 *
 * @property-read Carbon           $date
 * @property-read Coordinates      $coordinates
 * @property-read SolarCoordinates $solar
 * @property-read SolarCoordinates $prevSolar
 * @property-read SolarCoordinates $nextSolar
 * @property-read float           $approxTransit
 * @property-read float            $transit
 * @property-read float            $sunrise
 * @property-read float            $sunset
 */
class SolarTime
{

    /**
     * @var \Carbon\Carbon
     */
    protected $date;

    /**
     * @var Coordinates
     */
    protected $coordinates;

    /**
     * @var SolarCoordinates
     */
    protected $solar;

    /**
     * @var SolarCoordinates
     */
    protected $prevSolar;

    /**
     * @var SolarCoordinates
     */
    protected $nextSolar;

    /**
     * @var float
     */
    protected $approxTransit;

    /**
     * @var float
     */
    protected $transit;

    /**
     * @var float
     */
    protected $sunrise;

    /**
     * @var float
     */
    protected $sunset;

    /**
     * Solar constructor.
     *
     * @param \Carbon\Carbon $date
     * @param Coordinates    $coordinates
     *
     */
    public function __construct(Carbon $date, Coordinates $coordinates)
    {
        $solarAltitude = -50 / 60;
        $this->date = $date;
        $this->coordinates = $coordinates;
        $this->date->setTime(0, 0, 0);

        $this->solar = new SolarCoordinates(Astronomical::julianDate($date));
        $this->prevSolar = new SolarCoordinates(Astronomical::julianDate($date->subDay()));
        $this->nextSolar = new SolarCoordinates(Astronomical::julianDate($date->addDay()));

        $this->calcApproximateTransit()
            ->calcTransit()
            ->calcSunrise($solarAltitude)
            ->calcSunset($solarAltitude);
    }

    /**
     * get hour angle
     *
     * @param int  $angle
     * @param bool $afterTransit
     *
     * @return float
     */
    public function hourAngle(int $angle, bool $afterTransit)
    {
        return Astronomical::correctedHourAngle(
            $this->approxTransit,
            $angle,
            $this->coordinates,
            $afterTransit,
            $this->solar->apparentSiderealTime,
            $this->solar->rightAscension,
            $this->prevSolar->rightAscension,
            $this->nextSolar->rightAscension,
            $this->solar->declination,
            $this->prevSolar->declination,
            $this->nextSolar->declination
        );
    }

    /**
     * get afternoon
     *
     * @param int $shadowLength
     *
     * @return float
     */
    public function afternoon(int $shadowLength)
    {
        $tangent = abs($this->coordinates->latitude - $this->solar->declination);
        $inverse = $shadowLength + tan(Astronomical::degreesToRadians($tangent));
        $angle = Astronomical::radiansToDegrees(atan(1 / $inverse));

        return $this->hourAngle($angle, true);
    }

    /**
     * Calculate approximate transit
     *
     * @return $this
     */
    protected function calcApproximateTransit()
    {
        $this->approxTransit = Astronomical::approximateTransit(
            $this->coordinates->longitude,
            $this->solar->apparentSiderealTime,
            $this->solar->rightAscension
        );

        return $this;
    }

    /**
     * Calculate transit
     *
     * @return $this
     */
    protected function calcTransit()
    {
        $this->transit = Astronomical::correctedTransit(
            $this->approxTransit,
            $this->coordinates->longitude,
            $this->solar->apparentSiderealTime,
            $this->solar->rightAscension,
            $this->prevSolar->rightAscension,
            $this->nextSolar->rightAscension
        );

        return $this;
    }

    /**
     * calculate sunset
     *
     * @param $solarAltitude
     *
     * @return $this
     */
    protected function calcSunrise($solarAltitude)
    {
        $this->sunrise = Astronomical::correctedHourAngle(
            $this->approxTransit,
            $solarAltitude,
            $this->coordinates,
            false,
            $this->solar->apparentSiderealTime,
            $this->solar->rightAscension,
            $this->prevSolar->rightAscension,
            $this->nextSolar->rightAscension,
            $this->solar->declination,
            $this->prevSolar->declination,
            $this->nextSolar->declination
        );

        return $this;
    }

    /**
     * calculate sunset
     *
     * @param $solarAltitude
     *
     * @return $this
     */
    protected function calcSunset($solarAltitude)
    {
        $this->sunset = Astronomical::correctedHourAngle(
            $this->approxTransit,
            $solarAltitude,
            $this->coordinates,
            true,
            $this->solar->apparentSiderealTime,
            $this->solar->rightAscension,
            $this->prevSolar->rightAscension,
            $this->nextSolar->rightAscension,
            $this->solar->declination,
            $this->prevSolar->declination,
            $this->nextSolar->declination
        );

        return $this;
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