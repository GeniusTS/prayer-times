<?php

namespace GeniusTS\PrayerTimes;


use Carbon\Carbon;
use GeniusTS\PrayerTimes\Methods\Gulf;
use GeniusTS\PrayerTimes\Methods\World;
use GeniusTS\PrayerTimes\Methods\Qatar;
use GeniusTS\PrayerTimes\Methods\France;
use GeniusTS\PrayerTimes\Methods\UmQura;
use GeniusTS\PrayerTimes\Methods\Method;
use GeniusTS\PrayerTimes\Methods\Custom;
use GeniusTS\PrayerTimes\Methods\Kuwait;
use GeniusTS\PrayerTimes\Methods\Karachi;
use GeniusTS\PrayerTimes\Methods\Egyptian;
use GeniusTS\PrayerTimes\Methods\Singapore;
use GeniusTS\PrayerTimes\Methods\NorthAmerica;

/**
 * Class Prayer
 *
 * @package GeniusTS\PrayerTimes
 */
class Prayer
{

    /**
     * Calculation mathaheb
     */
    const MATHHAB_STANDARD = 1;
    const MATHHAB_HANAFI = 2;

    /**
     * Constant of high latitude rules
     */
    const HIGH_LATITUDE_MIDDLE_OF_NIGHT = 1;
    const HIGH_LATITUDE_SEVENTH_OF_NIGHT = 2;
    const HIGH_LATITUDE_TWILIGHT_ANGLE = 3;

    /**
     * constants of calculation methods
     */
    const METHOD_MUSLIM_WORLD_LEAGUE = 'world';
    const METHOD_UNIVERSITY_ISLAMIC_SCIENCE_KARACHI = 'karachi';
    const METHOD_EGYPTIAN_GENERAL_AUTHORITY = 'egyptian';
    const METHOD_NORTH_AMERICA_ISLAMIC_SOCIETY = 'north_america';
    const METHOD_UNION_ORGANIZATION_ISLAMIC_FRANCE = 'france';
    const METHOD_MAJLIS_UGAMA_ISLAM_SINGAPURA = 'singapore';
    const METHOD_UM_AL_QURA = 'um_qura';
    const METHOD_GULF_REGION = 'gulf';
    const METHOD_KUWAIT = 'kuwait';
    const METHOD_QATAR = 'qatar';
    const METHOD_CUSTOM = 'custom';

    /**
     * Available calculation method
     *
     * @var array
     */
    protected static $methods = [
        'world'         => World::class,
        'karachi'       => Karachi::class,
        'egyptian'      => Egyptian::class,
        'north_america' => NorthAmerica::class,
        'um_qura'       => UmQura::class,
        'gulf'          => Gulf::class,
        'kuwait'        => Kuwait::class,
        'qatar'         => Qatar::class,
        'france'        => France::class,
        'singapore'     => Singapore::class,
        'custom'        => Custom::class,
    ];

    /**
     * Available high latitude rules
     *
     * @var array<int>
     */
    protected static $mathaheb = [1, 2];

    /**
     * Available high latitude rules
     *
     * @var array<int>
     */
    protected static $highLatitudeRules = [1, 2, 3];

    /**
     * default method class name
     *
     * @var string
     */
    protected static $default_method_class = UmQura::class;

    /**
     * default method class name
     *
     * @var \GeniusTS\PrayerTimes\Methods\Method
     */
    protected static $default_method;

    /**
     * default calculation mathhab
     *
     * @var int
     */
    protected static $defaul_mathab = 1;

    /**
     * default high latitude rule
     *
     * @var int
     */
    protected static $default_highLatitudeRule = 1;

    /**
     * Adjustment minutes
     *
     * @var array<int>
     */
    protected static $default_adjustment = [
        'fajr'    => 0,
        'sunrise' => 0,
        'duhr'    => 0,
        'asr'     => 0,
        'maghrib' => 0,
        'isha'    => 0,
    ];

    /**
     * @var \GeniusTS\PrayerTimes\Methods\Method
     */
    protected $method;

    /**
     * high latitude rule
     *
     * @var int
     */
    protected $highLatitudeRule;

    /**
     * Calculation mathhab
     *
     * @var int
     */
    protected $mathhab;

    /**
     * Adjustment minutes
     *
     * @var array<int>
     */
    protected $adjustment;

    /**
     * night portions
     *
     * @var array
     */
    protected $nightPortions = ['fajr' => 0, 'isha' => 0];

    /**
     * position
     *
     * @var \GeniusTS\PrayerTimes\Coordinates
     */
    protected $coordinates;

    /**
     * set default method class name
     *
     * @param string $method
     *
     * @throws \GeniusTS\PrayerTimes\MethodNotSupportedException
     */
    public static function setDefaultMethodClass(string $method)
    {
        if (! array_key_exists($method, self::$methods))
        {
            throw new MethodNotSupportedException;
        }

        self::$default_method_class = self::$methods[$method];
    }

    /**
     * set default method
     *
     * @param mixed $method
     *
     * @throws \GeniusTS\PrayerTimes\MethodNotSupportedException
     */
    public static function setDefaultMethod($method)
    {
        self::$default_method = self::checkMethod($method);
    }

    /**
     * Set default mathhab value
     *
     * @param int $mathhab
     */
    public static function setDefaultMathhab(int $mathhab)
    {
        self::$defaul_mathab = self::checkMathhab($mathhab);
    }

    /**
     * Set default high latitude rule value
     *
     * @param int $value
     */
    public static function setDefaultHighLatitudeRule(int $value)
    {
        self::$defaul_mathab = self::checkHighLatitudeRule($value);
    }

    /**
     * Set default adjustments values
     *
     * @param int $fajr
     * @param int $sunrise
     * @param int $duhr
     * @param int $asr
     * @param int $maghrib
     * @param int $isha
     */
    public static function setDefaultAdjustments(int $fajr, int $sunrise, int $duhr, int $asr, int $maghrib, int $isha)
    {
        self::$default_adjustment = [
            'fajr'    => $fajr,
            'sunrise' => $sunrise,
            'duhr'    => $duhr,
            'asr'     => $asr,
            'maghrib' => $maghrib,
            'isha'    => $isha,
        ];
    }

    /**
     * Prayer constructor.
     *
     * @param \GeniusTS\PrayerTimes\Coordinates|null $coordinates
     */
    public function __construct(Coordinates $coordinates = null)
    {
        if (! self::$default_method)
        {
            self::$default_method = new self::$default_method_class;
        }

        $this->coordinates = $coordinates;

        $this->method = self::$default_method;
        $this->mathhab = self::$defaul_mathab;
        $this->highLatitudeRule = self::$default_highLatitudeRule;
        $this->adjustment = self::$default_adjustment;
    }

    /**
     * Set longitude property
     *
     * @param float $longitude
     * @param float $latitude
     *
     * @return $this
     */
    public function setCoordinates(float $longitude, float $latitude)
    {
        $this->coordinates = new Coordinates($longitude, $latitude);

        return $this;
    }

    /**
     * Set mathhab
     *
     * @param int $value
     *
     * @return $this
     * @throws \GeniusTS\PrayerTimes\MathhabNotSupportedException
     */
    public function setMathhab(int $value)
    {
        $this->mathhab = self::checkMathhab($value);

        return $this;
    }

    /**
     * Set high latitude rule
     *
     * @param int $value
     *
     * @return $this
     * @throws \GeniusTS\PrayerTimes\HighLatitudeRuleNotSupportedException
     */
    public function setHighLatitudeRule(int $value)
    {
        $this->highLatitudeRule = self::checkHighLatitudeRule($value);

        return $this;
    }

    /**
     * Set adjustments minutes
     *
     * @param int $fajr
     * @param int $sunrise
     * @param int $duhr
     * @param int $asr
     * @param int $maghrib
     * @param int $isha
     *
     * @return $this
     */
    public function setAdjustments(int $fajr, int $sunrise, int $duhr, int $asr, int $maghrib, int $isha)
    {
        $this->adjustment = [
            'fajr'    => $fajr,
            'sunrise' => $sunrise,
            'duhr'    => $duhr,
            'asr'     => $asr,
            'maghrib' => $maghrib,
            'isha'    => $isha,
        ];

        return $this;
    }

    /**
     * Set calculation method
     *
     * @param mixed $method
     *
     * @return $this
     * @throws \GeniusTS\PrayerTimes\MethodNotSupportedException
     */
    public function setMethod($method)
    {
        $this->method = self::checkMethod($method);

        return $this;
    }

    /**
     * get prayer times
     *
     * @param mixed $date
     *
     * @return \GeniusTS\PrayerTimes\Times
     */
    public function times($date)
    {
        if (! $date instanceof Carbon)
        {
            $date = new Carbon($date);
        }

        $this->calculateNightPortions();

        $solarTime = new SolarTime($date, $this->coordinates);

        $duhrTime = $this->buildTimeObject($solarTime->transit, $date);
        $sunriseTime = $this->buildTimeObject($solarTime->sunrise, $date);
        $maghribTime = $this->buildTimeObject($solarTime->sunset, $date);
        $asrTime = $this->buildTimeObject($solarTime->afternoon($this->mathhab), $date);
        $tomorrowSunrise = (new Carbon($sunriseTime))->addDay();
        $night = $tomorrowSunrise->timestamp - $maghribTime->timestamp;

        $fajrTime = $this->buildTimeObject($solarTime->hourAngle(-1 * $this->method()->fajrAngle(), false), $date);

        $nightFraction = (int) ($this->nightPortions['fajr'] * $night);
        $safeFajr = (new Carbon($sunriseTime))->addSeconds(-$nightFraction);

        if ($fajrTime == null || ! is_int($fajrTime->timestamp) || $safeFajr->gt($fajrTime))
        {
            $fajrTime = $safeFajr;
        }

        if ($this->method()->ishaInterval() > 0)
        {
            $ishaTime = (new Carbon($maghribTime))->addMinutes($this->method()->ishaInterval());
        }
        else
        {
            $ishaTime = $this->buildTimeObject($solarTime->hourAngle(-1 * $this->method()->ishaAngle(), true), $date);

            $nightFraction = $this->nightPortions['isha'] * $night;
            $safeIsha = (new Carbon($maghribTime))->addSeconds($nightFraction);

            if ($ishaTime == null || ! is_int($ishaTime->timestamp) || $safeIsha->lt($ishaTime))
            {
                $ishaTime = $safeIsha;
            }
        }

        // method based offsets
        $duhrOffset = $this->method()->duhrOffset();
        $maghribOffset = $this->method()->maghribOffset();

        return new Times(
            $this->roundedMinute($fajrTime->addMinutes($this->adjustment['fajr'])),
            $this->roundedMinute($sunriseTime->addMinutes($this->adjustment['sunrise'])),
            $this->roundedMinute($duhrTime->addMinutes($this->adjustment['duhr'] + $duhrOffset)),
            $this->roundedMinute($asrTime->addMinutes($this->adjustment['asr'])),
            $this->roundedMinute($maghribTime->addMinutes($this->adjustment['maghrib'] + $maghribOffset)),
            $this->roundedMinute($ishaTime->addMinutes($this->adjustment['isha'])),
            $this->method()->name()
        );
    }

    /**
     * get calculation method
     *
     * @return \GeniusTS\PrayerTimes\Methods\Method
     */
    protected function method()
    {
        if (! $this->method)
        {
            return $this->method = self::$default_method;
        }

        return $this->method;
    }

    /**
     * calculate night portions
     *
     * @return $this
     */
    protected function calculateNightPortions()
    {
        switch ($this->highLatitudeRule)
        {
            case static::HIGH_LATITUDE_MIDDLE_OF_NIGHT:
                $this->nightPortions = ['fajr' => 1 / 2, 'isha' => 1 / 2];
                break;
            case static::HIGH_LATITUDE_SEVENTH_OF_NIGHT:
                $this->nightPortions = ['fajr' => 1 / 7, 'isha' => 1 / 7];
                break;
            case static::HIGH_LATITUDE_TWILIGHT_ANGLE:
                $this->nightPortions = [
                    'fajr' => $this->method()->fajrAngle() / 60,
                    'isha' => $this->method()->ishaAngle() / 60,
                ];
                break;
        }

        return $this;
    }

    /**
     * Build a Carbon instance with date and time value of pray
     *
     * @param                $time
     * @param \Carbon\Carbon $date
     *
     * @return Carbon
     */
    protected function buildTimeObject($time, Carbon $date)
    {
        $timeObject = $this->getTimeFromNumber($time);

        return (new Carbon)
            ->setDateTime($date->year, $date->month, $date->day, $timeObject->hour, $timeObject->minute, $timeObject->second);
    }

    /**
     * Get the time from float time number
     *
     * @param $time
     *
     * @return object
     */
    protected function getTimeFromNumber($time)
    {
        $hour = (int) floor($time);
        $minute = (int) floor(($time - $hour) * 60);
        $second = (int) floor(($time - ($hour + $minute / 60)) * 60 * 60);

        return (object) compact('hour', 'minute', 'second');
    }

    /**
     * Round seconds
     *
     * @param \Carbon\Carbon $date
     *
     * @return Carbon
     */
    protected function roundedMinute(Carbon $date)
    {
        $seconds = $date->second;
        $offset = $seconds >= 30 ? 60 - $seconds : -1 * $seconds;

        return $date->addSeconds($offset);
    }

    /**
     * Check if method exists and return method instance
     *
     * @param $method
     *
     * @return mixed
     * @throws \GeniusTS\PrayerTimes\MethodNotSupportedException
     */
    protected static function checkMethod($method)
    {
        if ($method instanceof Method)
        {
            return $method;
        }

        if (array_key_exists($method, self::$methods))
        {
            return new self::$methods[$method];
        }

        throw new MethodNotSupportedException;
    }

    /**
     * Check if mathhab exists and return mathhab value
     *
     * @param int $mathhab
     *
     * @return int
     * @throws \GeniusTS\PrayerTimes\MathhabNotSupportedException
     */
    protected static function checkMathhab(int $mathhab)
    {
        if (! in_array($mathhab, self::$mathaheb))
        {
            throw new MathhabNotSupportedException;
        }

        return $mathhab;
    }

    /**
     * Check if high latitude rule exists and return valid value
     *
     * @param int $value
     *
     * @return int
     * @throws \GeniusTS\PrayerTimes\HighLatitudeRuleNotSupportedException
     */
    protected static function checkHighLatitudeRule(int $value)
    {
        if (! in_array($value, self::$highLatitudeRules))
        {
            throw new HighLatitudeRuleNotSupportedException();
        }

        return $value;
    }

}