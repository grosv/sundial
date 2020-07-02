<?php

declare(strict_types=1);

namespace Grosv\Sundial;

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
    private int $ts;
    private string $test;
    private array $matches;
    private array $boundary;

    public function __construct()
    {
        $this->language  = 'eng';
        $this->boundary  = ['start' => 0, 'end' => 2147483647];
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
        $try = strtotime($string);
        if (abs((int) $try) > 86400) {
            $this->ts = (int) $try;

            return $this;
        }
        $this->loadLanguage();
        $this->string = strtolower(' ' . $string . ' ');
        $this->test   = (string) preg_replace('/[\W]/', ' ', $this->string);
        $this->parseNumericFormats();
        $this->year   = $this->year ?? $this->getYear();
        $this->month  = $this->month ?? $this->getMonth();
        $this->date   = $this->date ?? $this->getDate();
        $this->day    = $this->day ?? $this->getDay();
        $this->time   = $this->time ?? $this->getTime();

        $this->ts = (int) strtotime(implode(' ', [$this->date, $this->month, $this->year, $this->time]));

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
        return date('d');
    }

    public function getDay(): string
    {
        return '';
    }

    public function getTime(): string
    {
        return date('h:i:s a');
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
            $this->month = (string) $this->matches['months'][ltrim((string) $m, '0')][0];
            $this->date  = (string) $d;
            $this->year  = (string) $y;
        }

        return;
    }

    public function toFormat(string $format): string
    {
        return date($format, $this->ts);
    }

    public function toTimestamp(): int
    {
        return $this->ts;
    }
}
