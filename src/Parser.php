<?php

declare(strict_types=1);

namespace Grosv\NaturalTime;

/**
 * @internal
 */
final class Parser
{
    private string $language;
    private string $string;
    private string $year;
    private string $month;
    private string $date;
    private string $time;
    private int $ts;
    private array $matches;

    public function __construct()
    {
        $this->language = 'english';
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

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
        $this->year   = $this->getYear();
        $this->month  = $this->getMonth();
        $this->date   = $this->getDate();
        $this->time   = $this->getTime();

        $this->ts = (int) strtotime(implode(' ', [$this->date, $this->month, $this->year, $this->time]));

        return $this;
    }

    public function getYear(): string
    {
        $test = (string) preg_replace('/[\W]/', ' ', $this->string);
        preg_match('/(^|\s)(\d{4})(\s|$)/', $test, $matches);
        if (is_array($matches)) {
            foreach ($matches as $y) {
                if ((int) $y > 1969) {
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
                    return $k;
                }
            }
        }

        return date('F');
    }

    public function getDate(): string
    {
        return date('d');
    }

    public function getTime(): string
    {
        return date('h:i:s a');
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
