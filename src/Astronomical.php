<?php

namespace GeniusTS\PrayerTimes;


use Carbon\Carbon;

/**
 * Class Astronomical
 *
 * @package GeniusTS\PrayerTimes
 */
class Astronomical
{

    /**
     * returns the integer part of a number by removing any fractional digits
     *
     * @param float $x
     *
     * @return float
     */
    public static function trunc(float $x)
    {
        return $x < 0 ? ceil($x) : floor($x);
    }

    /**
     * Convert radian to degree
     *
     * @param float $radians
     *
     * @return float
     */
    public static function radiansToDegrees(float $radians)
    {
        return ($radians * 180.0) / pi();
    }

    /**
     * get closest angle to an angle
     *
     * @param float $angle
     *
     * @return float
     */
    public static function closestAngle(float $angle)
    {
        if ($angle >= -180 && $angle <= 180)
        {
            return $angle;
        }

        return $angle - (360 * round($angle / 360));
    }

    /**
     * @param float $number
     * @param float $max
     *
     * @return float
     */
    public static function normalizeWithBound(float $number, float $max)
    {
        return $number - ($max * (floor($number / $max)));
    }

    /**
     * @param float $angle
     *
     * @return float
     */
    public static function unwindAngle(float $angle)
    {
        return static::normalizeWithBound($angle, 360);
    }

    /**
     * The geometric mean longitude of the sun in degrees.
     *
     * @param float $julianCentury
     *
     * @return float
     */
    public static function meanSolarLongitude(float $julianCentury)
    {
        $term1 = 280.4664567;
        $term2 = 36000.76983 * $julianCentury;
        $term3 = 0.0003032 * pow($julianCentury, 2);
        $L0 = $term1 + $term2 + $term3;

        return static::unwindAngle($L0);
    }

    /**
     * The geometric mean longitude of the moon in degrees.
     *
     * @param $julianCentury
     *
     * @return float
     */
    public static function meanLunarLongitude(float $julianCentury)
    {
        /* Equation from Astronomical Algorithms page 144 */
        $term1 = 218.3165;
        $term2 = 481267.8813 * $julianCentury;
        $Lp = $term1 + $term2;

        return static::unwindAngle($Lp);
    }

    /**
     * Calculate ascending lunar node longitude
     *
     * @param $julianCentury
     *
     * @return float
     */
    public static function ascendingLunarNodeLongitude($julianCentury)
    {
        /* Equation from Astronomical Algorithms page 144 */
        $term1 = 125.04452;
        $term2 = 1934.136261 * $julianCentury;
        $term3 = 0.0020708 * pow($julianCentury, 2);
        $term4 = pow($julianCentury, 3) / 450000;
        $omega = $term1 - $term2 + $term3 + $term4;

        return static::unwindAngle($omega);
    }

    /**
     * The mean anomaly of the sun.
     *
     * @param float $julianCentury
     *
     * @return int
     */
    public static function meanSolarAnomaly(float $julianCentury)
    {
        /* Equation from Astronomical Algorithms page 163 */
        $term1 = 357.52911;
        $term2 = 35999.05029 * $julianCentury;
        $term3 = 0.0001537 * pow($julianCentury, 2);
        $M = $term1 + $term2 - $term3;

        return static::unwindAngle($M);
    }

    /**
     * The Sun's equation of the center in degrees.
     *
     * @param float $julianCentury
     * @param float $meanAnomaly
     *
     * @return float
     */
    public static function solarEquationOfTheCenter(float $julianCentury, float $meanAnomaly)
    {
        /* Equation from Astronomical Algorithms page 164 */
        $Mrad = static::degreesToRadians($meanAnomaly);
        $term1 = (1.914602 - (0.004817 * $julianCentury) - (0.000014 * pow($julianCentury, 2))) * sin($Mrad);
        $term2 = (0.019993 - (0.000101 * $julianCentury)) * sin(2 * $Mrad);
        $term3 = 0.000289 * sin(3 * $Mrad);

        return $term1 + $term2 + $term3;
    }

    /**
     * Convert degree to radian
     *
     * @param float $degrees
     *
     * @return float
     */
    public static function degreesToRadians(float $degrees)
    {
        return ($degrees * pi()) / 180.0;
    }

    /**
     * The apparent longitude of the Sun, referred to the true equinox of the date.
     *
     * @param float $julianCentury
     * @param float $meanLongitude
     *
     * @return float int
     */
    public static function apparentSolarLongitude(float $julianCentury, float $meanLongitude)
    {
        /* Equation from Astronomical Algorithms page 164 */
        $longitude = $meanLongitude + static::solarEquationOfTheCenter($julianCentury, static::meanSolarAnomaly($julianCentury));
        $Omega = 125.04 - (1934.136 * $julianCentury);
        $Lambda = $longitude - 0.00569 - (0.00478 * sin(static::degreesToRadians($Omega)));

        return static::unwindAngle($Lambda);
    }

    /**
     * The mean obliquity of the ecliptic, formula
     * adopted by the International Astronomical Union.
     * Represented in degrees.
     *
     * @param float $julianCentury
     *
     * @return float
     */
    public static function meanObliquityOfTheEcliptic(float $julianCentury)
    {
        /* Equation from Astronomical Algorithms page 147 */
        $term1 = 23.439291;
        $term2 = 0.013004167 * $julianCentury;
        $term3 = 0.0000001639 * pow($julianCentury, 2);
        $term4 = 0.0000005036 * pow($julianCentury, 3);

        return $term1 - $term2 - $term3 + $term4;
    }

    /**
     * The mean obliquity of the ecliptic, corrected for
     * calculating the apparent position of the sun, in degrees.
     *
     * @param float $julianCentury
     * @param float $meanObliquityOfTheEcliptic
     *
     * @return mixed
     */
    public static function apparentObliquityOfTheEcliptic(float $julianCentury, float $meanObliquityOfTheEcliptic)
    {
        /* Equation from Astronomical Algorithms page 165 */
        $O = 125.04 - (1934.136 * $julianCentury);

        return $meanObliquityOfTheEcliptic + (0.00256 * cos(static::degreesToRadians($O)));
    }

    /**
     * Mean sidereal time, the hour angle of the vernal equinox, in degrees.
     *
     * @param float $julianCentury
     *
     * @return float
     */
    public static function meanSiderealTime(float $julianCentury)
    {
        /* Equation from Astronomical Algorithms page 165 */
        $JD = ($julianCentury * 36525) + 2451545.0;
        $term1 = 280.461061837;
        $term2 = 360.98564736629 * ($JD - 2451545);
        $term3 = 0.000387933 * pow($julianCentury, 2);
        $term4 = pow($julianCentury, 3) / 38710000;
        $Theta = $term1 + $term2 + $term3 - $term4;

        return static::unwindAngle($Theta);
    }

    /**
     * @param float $solarLongitude
     * @param float $lunarLongitude
     * @param float $ascendingNode
     *
     * @return float
     */
    public static function nutationInLongitude(float $solarLongitude, float $lunarLongitude, float $ascendingNode)
    {
        /* Equation from Astronomical Algorithms page 144 */
        $term1 = (-17.2 / 3600) * sin(static::degreesToRadians($ascendingNode));
        $term2 = (1.32 / 3600) * sin(2 * static::degreesToRadians($solarLongitude));
        $term3 = (0.23 / 3600) * sin(2 * static::degreesToRadians($lunarLongitude));
        $term4 = (0.21 / 3600) * sin(2 * static::degreesToRadians($ascendingNode));

        return $term1 - $term2 - $term3 + $term4;
    }

    /**
     * @param float $solarLongitude
     * @param float $lunarLongitude
     * @param float $ascendingNode
     *
     * @return float
     */
    public static function nutationInObliquity(float $solarLongitude, float $lunarLongitude, float $ascendingNode)
    {
        /* Equation from Astronomical Algorithms page 144 */
        $term1 = (9.2 / 3600) * cos(static::degreesToRadians($ascendingNode));
        $term2 = (0.57 / 3600) * cos(2 * static::degreesToRadians($solarLongitude));
        $term3 = (0.10 / 3600) * cos(2 * static::degreesToRadians($lunarLongitude));
        $term4 = (0.09 / 3600) * cos(2 * static::degreesToRadians($ascendingNode));

        return $term1 + $term2 + $term3 - $term4;
    }

    /**
     * @param float $observerLatitude
     * @param float $declination
     * @param float $localHourAngle
     *
     * @return float
     */
    public static function altitudeOfCelestialBody(float $observerLatitude, float $declination, float $localHourAngle)
    {
        /* Equation from Astronomical Algorithms page 93 */
        $term1 = sin(static::degreesToRadians($observerLatitude)) * sin(static::degreesToRadians($declination));
        $term2 = cos(static::degreesToRadians($observerLatitude)) * cos(static::degreesToRadians($declination)) * cos(static::degreesToRadians($localHourAngle));

        return static::radiansToDegrees(asin($term1 + $term2));
    }

    /**
     * @param float $longitude
     * @param float $siderealTime
     * @param float $rightAscension
     *
     * @return float
     */
    public static function approximateTransit(float $longitude, float $siderealTime, float $rightAscension)
    {
        /* Equation from page Astronomical Algorithms 102 */
        $Lw = $longitude * -1;

        return static::normalizeWithBound(($rightAscension + $Lw - $siderealTime) / 360, 1);
    }

    /**
     * The time at which the sun is at its highest point in the sky (in universal time)
     *
     * @param float $approximateTransit
     * @param float $longitude
     * @param float $siderealTime
     * @param float $rightAscension
     * @param float $previousRightAscension
     * @param float $nextRightAscension
     *
     * @return float
     */
    public static function correctedTransit(
        float $approximateTransit,
        float $longitude,
        float $siderealTime,
        float $rightAscension,
        float $previousRightAscension,
        float $nextRightAscension
    )
    {
        /* Equation from page Astronomical Algorithms 102 */
        $Lw = $longitude * -1;
        $Theta = static::unwindAngle(($siderealTime + (360.985647 * $approximateTransit)));
        $a = static::unwindAngle(static::interpolateAngles($rightAscension, $previousRightAscension, $nextRightAscension, $approximateTransit));
        $H = static::closestAngle($Theta - $Lw - $a);
        $dm = $H / -360;

        return ($approximateTransit + $dm) * 24;
    }

    /**
     * @param float       $approximateTransit
     * @param float       $angle
     * @param Coordinates $coordinates
     * @param bool        $afterTransit
     * @param float       $siderealTime
     * @param float       $rightAscension
     * @param float       $previousRightAscension
     * @param float       $nextRightAscension
     * @param float       $declination
     * @param float       $previousDeclination
     * @param float       $nextDeclination
     *
     * @return float
     */
    public static function correctedHourAngle(
        float $approximateTransit,
        float $angle,
        Coordinates $coordinates,
        bool $afterTransit,
        float $siderealTime,
        float $rightAscension,
        float $previousRightAscension,
        float $nextRightAscension,
        float $declination,
        float $previousDeclination,
        float $nextDeclination
    )
    {
        /* Equation from page Astronomical Algorithms 102 */
        $Lw = $coordinates->longitude * -1;
        $term1 = sin(static::degreesToRadians($angle)) - (sin(static::degreesToRadians($coordinates->latitude)) * sin(static::degreesToRadians($declination)));
        $term2 = cos(static::degreesToRadians($coordinates->latitude)) * cos(static::degreesToRadians($declination));
        $H0 = static::radiansToDegrees(acos($term1 / $term2));
        $m = $afterTransit ? $approximateTransit + ($H0 / 360) : $approximateTransit - ($H0 / 360);
        $Theta = static::unwindAngle(($siderealTime + (360.985647 * $m)));
        $a = static::unwindAngle(static::interpolateAngles($rightAscension, $previousRightAscension, $nextRightAscension, $m));
        $delta = static::interpolate($declination, $previousDeclination, $nextDeclination, $m);
        $H = ($Theta - $Lw - $a);
        $h = static::altitudeOfCelestialBody($coordinates->latitude, $delta, $H);
        $term3 = $h - $angle;
        $term4 = 360 * cos(static::degreesToRadians($delta)) * cos(static::degreesToRadians($coordinates->latitude)) * sin(static::degreesToRadians($H));
        $dm = $term3 / $term4;

        return ($m + $dm) * 24;
    }

    /**
     * Interpolation of a value given equidistant
     * previous and next values and a factor
     * equal to the fraction of the interpolated
     * point's time over the time between values.
     *
     *
     * @param float $y2
     * @param float $y1
     * @param float $y3
     * @param float $n
     *
     * @return float
     */
    public static function interpolate(float $y2, float $y1, float $y3, float $n)
    {
        /* Equation from Astronomical Algorithms page 24 */
        $a = $y2 - $y1;
        $b = $y3 - $y2;
        $c = $b - $a;

        return $y2 + (($n / 2) * ($a + $b + ($n * $c)));
    }

    /**
     * Interpolation of three angles, accounting for
     * angle unwinding.
     *
     *
     * @param float $y2
     * @param float $y1
     * @param float $y3
     * @param float $n
     *
     * @return float
     */
    public static function interpolateAngles(float $y2, float $y1, float $y3, float $n)
    {
        /* Equation from Astronomical Algorithms page 24 */
        $a = static::unwindAngle($y2 - $y1);
        $b = static::unwindAngle($y3 - $y2);
        $c = $b - $a;

        return $y2 + (($n / 2) * ($a + $b + ($n * $c)));
    }

    /**
     * Get julian day of a date
     *
     * @param \Carbon\Carbon $date
     *
     * @return float
     */
    public static function julianDate(Carbon $date)
    {
        return static::julianDay($date->year, $date->month, $date->day);
    }

    /**
     * The Julian Day for a given Gregorian date.
     *
     * @param int $year
     * @param int $month
     * @param int $day
     *
     * @return float
     */
    public static function julianDay(int $year, int $month, int $day)
    {
        /* Equation from Astronomical Algorithms page 60 */
        $Y = $month > 2 ? $year : $year - 1;
        $M = $month > 2 ? $month : $month + 12;

        $A = static::trunc($Y / 100);
        $B = 2 - $A + static::trunc($A / 4);

        $i0 = static::trunc(365.25 * ($Y + 4716));
        $i1 = static::trunc(30.6001 * ($M + 1));

        return $i0 + $i1 + $day + $B - 1524.5;
    }

    /**
     * Julian century from the epoch.
     *
     * @param float $julianDay
     *
     * @return float
     */
    public static function julianCentury(float $julianDay)
    {
        /* Equation from Astronomical Algorithms page 163 */
        return ($julianDay - 2451545.0) / 36525;
    }

    /**
     * Whether or not a year is a leap year (has 366 days).
     *
     * @param int $year
     *
     * @return bool
     */
    public static function isLeapYear(int $year)
    {
        if ($year % 4 != 0)
        {
            return false;
        }

        if ($year % 100 == 0 && $year % 400 != 0)
        {
            return false;
        }

        return true;
    }

    /**
     * @param float          $latitude
     * @param int            $dayOfYear
     * @param int            $year
     * @param \Carbon\Carbon $sunrise
     *
     * @return Carbon
     */
    public static function seasonAdjustedMorningTwilight(float $latitude, int $dayOfYear, int $year, Carbon $sunrise)
    {
        $a = 75 + ((28.65 / 55.0) * abs($latitude));
        $b = 75 + ((19.44 / 55.0) * abs($latitude));
        $c = 75 + ((32.74 / 55.0) * abs($latitude));
        $d = 75 + ((48.10 / 55.0) * abs($latitude));

        $dyy = static::daysSinceSolstice($dayOfYear, $year, $latitude);

        if ($dyy < 91)
        {
            $adjustment = $a + ($b - $a) / 91.0 * $dyy;
        }
        else if ($dyy < 137)
        {
            $adjustment = $b + ($c - $b) / 46.0 * ($dyy - 91);
        }
        else if ($dyy < 183)
        {
            $adjustment = $c + ($d - $c) / 46.0 * ($dyy - 137);
        }
        else if ($dyy < 229)
        {
            $adjustment = $d + ($c - $d) / 46.0 * ($dyy - 183);
        }
        else if ($dyy < 275)
        {
            $adjustment = $c + ($b - $c) / 46.0 * ($dyy - 229);
        }
        else
        {
            $adjustment = $b + ($a - $b) / 91.0 * ($dyy - 275);
        }

        return (new Carbon($sunrise))->addSeconds(round($adjustment * -60.0));
    }

    /**
     * @param float          $latitude
     * @param int            $dayOfYear
     * @param int            $year
     * @param \Carbon\Carbon $sunset
     *
     * @return Carbon
     */
    public static function seasonAdjustedEveningTwilight(float $latitude, int $dayOfYear, int $year, Carbon $sunset)
    {
        $a = 75 + ((25.60 / 55.0) * abs($latitude));
        $b = 75 + ((2.050 / 55.0) * abs($latitude));
        $c = 75 - ((9.210 / 55.0) * abs($latitude));
        $d = 75 + ((6.140 / 55.0) * abs($latitude));

        $dyy = static::daysSinceSolstice($dayOfYear, $year, $latitude);

        if ($dyy < 91)
        {
            $adjustment = $a + ($b - $a) / 91.0 * $dyy;
        }
        else if ($dyy < 137)
        {
            $adjustment = $b + ($c - $b) / 46.0 * ($dyy - 91);
        }
        else if ($dyy < 183)
        {
            $adjustment = $c + ($d - $c) / 46.0 * ($dyy - 137);
        }
        else if ($dyy < 229)
        {
            $adjustment = $d + ($c - $d) / 46.0 * ($dyy - 183);
        }
        else if ($dyy < 275)
        {
            $adjustment = $c + ($b - $c) / 46.0 * ($dyy - 229);
        }
        else
        {
            $adjustment = $b + ($a - $b) / 91.0 * ($dyy - 275);
        }

        return (new Carbon($sunset))->addSeconds(round($adjustment * 60.0));
    }

    /**
     * @param int   $dayOfYear
     * @param int   $year
     * @param float $latitude
     *
     * @return float
     */
    public static function daysSinceSolstice(int $dayOfYear, int $year, float $latitude)
    {
        $northernOffset = 10;
        $southernOffset = static::isLeapYear($year) ? 173 : 172;
        $daysInYear = static::isLeapYear($year) ? 366 : 365;

        if ($latitude >= 0)
        {
            $daysSinceSolstice = $dayOfYear + $northernOffset;
            if ($daysSinceSolstice >= $daysInYear)
            {
                $daysSinceSolstice = $daysSinceSolstice - $daysInYear;
            }
        }
        else
        {
            $daysSinceSolstice = $dayOfYear - $southernOffset;
            if ($daysSinceSolstice < 0)
            {
                $daysSinceSolstice = $daysSinceSolstice + $daysInYear;
            }
        }

        return $daysSinceSolstice;
    }
}