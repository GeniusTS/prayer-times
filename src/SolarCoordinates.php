<?php

namespace GeniusTS\PrayerTimes;


/**
 * declination: The declination of the sun, the angle between
 * the rays of the Sun and the plane of the Earth's
 * equator, in degrees.
 *
 * rightAscension: Right ascension of the Sun, the angular distance on the
 * celestial equator from the vernal equinox to the hour circle,
 * in degrees.
 *
 * apparentSiderealTime: Apparent sidereal time, the hour angle of the vernal
 * equinox, in degrees.
 *
 * @property-read float $declination
 * @property-read float $rightAscension
 * @property-read float $apparentSiderealTime
 */
class SolarCoordinates
{

    /**
     * @var float
     */
    protected $declination;

    /**
     * @var float
     */
    protected $rightAscension;

    /**
     * @var float
     */
    protected $apparentSiderealTime;

    /**
     * SolarCoordinates constructor.
     *
     * @param string $julianDay
     */
    public function __construct(string $julianDay)
    {
        $T = Astronomical::julianCentury($julianDay);
        $L0 = Astronomical::meanSolarLongitude($T);
        $Lp = Astronomical::meanLunarLongitude($T);
        $Omega = Astronomical::ascendingLunarNodeLongitude($T);
        $Lambda = Astronomical::degreesToRadians(Astronomical::apparentSolarLongitude($T, $L0));
        $Theta0 = Astronomical::meanSiderealTime($T);
        $dPsi = Astronomical::nutationInLongitude($L0, $Lp, $Omega);
        $dEpsilon = Astronomical::nutationInObliquity($L0, $Lp, $Omega);
        $Epsilon0 = Astronomical::meanObliquityOfTheEcliptic($T);
        $EpsilonApparent = Astronomical::degreesToRadians(Astronomical::apparentObliquityOfTheEcliptic($T, $Epsilon0));

        /* Equation from Astronomical Algorithms page 165 */
        $this->declination = Astronomical::radiansToDegrees(asin(sin($EpsilonApparent) * sin($Lambda)));

        /* Equation from Astronomical Algorithms page 165 */
        $this->rightAscension = Astronomical::unwindAngle(
            Astronomical::radiansToDegrees(
                atan2(cos($EpsilonApparent) * sin($Lambda), cos($Lambda))
            )
        );

        /* Equation from Astronomical Algorithms page 88 */
        $this->apparentSiderealTime = $Theta0 + ((($dPsi * 3600) * cos(Astronomical::degreesToRadians($Epsilon0 + $dEpsilon))) / 3600);
    }

    /**
     * @param string $attribute
     *
     * @return mixed
     */
    public function __get($attribute)
    {
        return $this->{$attribute};
    }
}