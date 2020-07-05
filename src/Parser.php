<?php

declare(strict_types=1);

namespace Grosv\Sundial;

use DateTime;

/**
 * @internal
 */
final class Parser
{
    private string $language;
    private string $string;
    private string $year;
    private string $month;
    private string $day;
    private string $date;
    private string $time;
    private int $ts = 0;
    private string $test;
    private array $matches;
    private array $boundary = ['start' => 1, 'end' => 2147483647];

    public function __construct()
    {
        $this->language  = 'eng';
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function setBetween(int $start, int $end): self
    {
        $this->boundary = ['start' => $start, 'end' => $end];

        return $this;
    }

    public function loadLanguage(): self
    {
        $this->matches = (new Languages())->load($this->language);

        return $this;
    }

    public function parse(string $string): self
    {
        $this->loadLanguage();
        $this->string = strtolower(' ' . $string . ' ');
        $this->test   = (string) preg_replace('/[\W]/', ' ', $this->string);
        $this->parseNumericFormats();
        $this->year   = $this->year ?? $this->getYear();
        $this->month  = $this->month ?? $this->getMonth();
        $this->date   = $this->date ?? $this->getDate();
        $this->day    = $this->day ?? $this->getDay();
        $this->time   = $this->time ?? $this->getTime();

        $this->calculateTime();

        return $this;
    }

    public function getYear(): string
    {
        preg_match('/(^|\s)(\d{4})(\s|$)/', $this->test, $matches);
        if (is_array($matches)) {
            foreach ($matches as $y) {
                if ((int) $y >= (int) date('Y', $this->boundary['start'])) {
                    return $y;
                }
            }
        }

        return date('Y');
    }

    public function getMonth(): string
    {
        foreach ($this->matches['months'] as $k => $v) {
            foreach ($v as $match) {
                if ((bool) preg_match("/$match/i", $this->string)) {
                    return $v[0];
                }
            }
        }

        return '';
    }

    public function getDate(): string
    {
        $test = preg_replace('/(?<=[0-9])(?:st|nd|rd|th)/', '', $this->test);
        foreach (explode(' ', (string) $test) as $word) {
            if (is_numeric($word) && (int) $word > 0 && (int) $word < 32) {
                return $word;
            }
        }

        return '';
    }

    public function getDay(): string
    {
        foreach ($this->matches['days'] as $k => $v) {
            foreach ($v as $day) {
                if ((bool) preg_match("/$day/", $this->test)) {
                    return $k;
                }
            }
        }

        return '';
    }

    public function getTime(): string
    {
        foreach ($this->matches['now'] as $word) {
            if (in_array(trim($word), explode(' ', $this->test), true)) {
                return date('H:i:s');
            }
        }
        preg_match('/((1[0-2]|0?[1-9]):?([0-5][0-9])? ?([AaPp][Mm])) /', $this->string, $matches);
        if (is_array($matches) && sizeof($matches) > 0) {
            return $matches[0] ?? '';
        }
        preg_match('/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])? /', $this->string, $matches);
        if (is_array($matches) && sizeof($matches) > 0) {
            return $matches[0] ?? '';
        }

        return '';
    }

    public function parseNumericFormats(): void
    {
        $m = $d = $y = 0;
        preg_match('/\d{1,2}\/\d{1,2}\/\d{4}/', $this->string, $matches);
        if (is_array($matches)) {
            foreach ($matches as $match) {
                [$m, $d, $y] = explode('/', $match);
            }
        }
        preg_match('/\d{1,2}-\d{1,2}-\d{4}/', $this->string, $matches);
        if (is_array($matches)) {
            foreach ($matches as $match) {
                [$d, $m, $y] = explode('-', $match);
            }
        }
        preg_match('/\d{4}-\d{1,2}-\d{1,2}/', $this->string, $matches);
        if (is_array($matches)) {
            foreach ($matches as $match) {
                [$y, $m, $d] = explode('-', $match);
            }
        }

        if (checkdate((int) $m, (int) $d, (int) $y)) {
            $this->month = trim((string) $this->matches['months'][ltrim((string) $m, '0')][0]);
            $this->date  = (string) $d;
            $this->year  = (string) $y;
        }

        return;
    }

    /**
     * @throws NoDateOrTimeStringFoundException
     * @throws \Exception
     */
    public function calculateTime(): void
    {
        $string = null;

        if ((bool) $this->time) {
            $string = $this->time;
        }

        if ((bool) $this->day) {
            $string = $this->day;
        }

        if ((bool) $this->day && (bool) $this->time) {
            $string = implode(' ', [$this->day, $this->time]);
        }

        if ((bool) $this->month && (bool) $this->date) {
            $string = implode(' ', [$this->date, $this->month, $this->year, $this->time]);
        }

        if (is_null($string)) {
            throw new NoDateOrTimeStringFoundException('Unable to find a date or time string to parse.');
        }

        $ts = new DateTime($string);

        $this->ts = $ts->getTimestamp();
    }

    /**
     * @throws DateTimeOutOfRangeException
     */
    public function toFormat(string $format): string
    {
        if ($this->boundary['start'] > $this->ts || $this->ts > $this->boundary['end']) {
            throw new  DateTimeOutOfRangeException('Date / Time Outside of Range');
        }

        return date($format, $this->ts);
    }

    /**
     * @throws DateTimeOutOfRangeException
     */
    public function toTimestamp(): int
    {
        if ($this->boundary['start'] > $this->ts || $this->ts > $this->boundary['end']) {
            throw new DateTimeOutOfRangeException('Date / Time Outside of Range');
        }

        return $this->ts;
    }
}
