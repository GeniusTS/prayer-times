<?php

namespace GeniusTS\PrayerTimes;


use Carbon\Carbon;
use InvalidArgumentException;

/**
 * Class Times
 *
 * @package GeniusTS\PrayerTimes
 *
 * @property-read \Carbon\Carbon fajr
 * @property-read \Carbon\Carbon sunrise
 * @property-read \Carbon\Carbon duhr
 * @property-read \Carbon\Carbon asr
 * @property-read \Carbon\Carbon maghrib
 * @property-read \Carbon\Carbon isha
 * @property-read string         $method
 */
class Times
{

    /**
     * Times constants
     */
    const TIME_FAJR = 'fajr';
    const TIME_SUNRISE = 'sunrise';
    const TIME_DUHR = 'duhr';
    const TIME_ASR = 'asr';
    const TIME_MAGHRIB = 'maghrib';
    const TIME_ISHA = 'isha';

    /**
     * Available times
     *
     * @var array
     */
    protected $times = [
        self::TIME_FAJR,
        self::TIME_SUNRISE,
        self::TIME_DUHR,
        self::TIME_ASR,
        self::TIME_MAGHRIB,
        self::TIME_ISHA,
    ];

    /**
     * @var \Carbon\Carbon
     */
    protected $fajr;

    /**
     * @var \Carbon\Carbon
     */
    protected $sunrise;

    /**
     * @var \Carbon\Carbon
     */
    protected $duhr;

    /**
     * @var \Carbon\Carbon
     */
    protected $asr;

    /**
     * @var \Carbon\Carbon
     */
    protected $maghrib;

    /**
     * @var \Carbon\Carbon
     */
    protected $isha;

    /**
     * @var string
     */
    protected $method;

    /**
     * Times constructor.
     *
     * @param \Carbon\Carbon $fajr
     * @param \Carbon\Carbon $sunrise
     * @param \Carbon\Carbon $duhr
     * @param \Carbon\Carbon $asr
     * @param \Carbon\Carbon $maghrib
     * @param \Carbon\Carbon $isha
     * @param string         $method
     */
    public function __construct(Carbon $fajr,
        Carbon $sunrise,
        Carbon $duhr,
        Carbon $asr,
        Carbon $maghrib,
        Carbon $isha,
        string $method)
    {
        $this->fajr = $fajr;
        $this->sunrise = $sunrise;
        $this->duhr = $duhr;
        $this->asr = $asr;
        $this->maghrib = $maghrib;
        $this->isha = $isha;
        $this->method = $method;
    }

    /**
     * Set time zone
     *
     * @param string $timezone
     *
     * @return $this
     */
    public function setTimeZone(string $timezone)
    {
        foreach ($this->times as $time)
        {
            $this->{$time}->setTimezone($timezone);
        }

        return $this;
    }

    /**
     * get prayer time by name
     *
     * @param $prayer
     *
     * @return \Carbon\Carbon|null
     */
    public function timeForPrayer(string $prayer)
    {
        switch ($prayer)
        {
            case static::TIME_FAJR:
                return $this->fajr;
            case static::TIME_SUNRISE:
                return $this->sunrise;
            case static::TIME_DUHR:
                return $this->duhr;
            case static::TIME_ASR:
                return $this->asr;
            case static::TIME_MAGHRIB:
                return $this->maghrib;
            case static::TIME_ISHA:
                return $this->isha;
            default:
                return null;
        }
    }

    /**
     * get current prayer name
     *
     * @param \Carbon\Carbon|null $date
     *
     * @return null|string
     */
    public function currentPrayer(Carbon $date = null)
    {
        if (! $date instanceof Carbon)
        {
            $date = new Carbon();
        }

        if ($date->gte($this->isha))
        {
            return static::TIME_ISHA;
        }
        elseif ($date->gte($this->maghrib))
        {
            return static::TIME_MAGHRIB;
        }
        elseif ($date->gte($this->asr))
        {
            return static::TIME_ASR;
        }
        elseif ($date->gte($this->duhr))
        {
            return static::TIME_DUHR;
        }
        elseif ($date->gte($this->sunrise))
        {
            return static::TIME_SUNRISE;
        }
        elseif ($date->gte($this->fajr))
        {
            return static::TIME_FAJR;
        }

        return null;
    }

    /**
     * Get next prayer name
     *
     * @param \Carbon\Carbon|null $date
     *
     * @return null|string
     */
    public function nextPrayer(Carbon $date = null)
    {
        if (! $date instanceof Carbon)
        {
            $date = new Carbon();
        }

        if ($date->gte($this->isha))
        {
            return null;
        }
        elseif ($date->gte($this->maghrib))
        {
            return static::TIME_ISHA;
        }
        elseif ($date->gte($this->asr))
        {
            return static::TIME_MAGHRIB;
        }
        elseif ($date->gte($this->duhr))
        {
            return static::TIME_ASR;
        }
        elseif ($date->gte($this->sunrise))
        {
            return static::TIME_DUHR;
        }
        elseif ($date->gte($this->fajr))
        {
            return static::TIME_SUNRISE;
        }

        return static::TIME_FAJR;
    }

    /**
     * @param $attribute
     *
     * @return mixed
     */
    public function __get($attribute)
    {
        if (in_array($attribute, $this->times))
        {
            return $this->{$attribute};
        }

        throw new InvalidArgumentException("Undefined argument '{$attribute}'");
    }
}
