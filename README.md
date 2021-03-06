# Sundial

Natural Language Date / Time String Parsing for PHP 7.4+

<p align="center">
    <img src="https://raw.githubusercontent.com/grosv/sundial/main/docs/sundial.jpg" height="300" alt="">
</p>
<p align="center">
    <a href="https://github.com/grosv/sundial/actions"><img alt="GitHub Workflow Status (main)" src="https://img.shields.io/github/workflow/status/grosv/sundial/Tests/main"></a>
    <a href="https://packagist.org/packages/grosv/sundial"><img alt="Total Downloads" src="https://img.shields.io/packagist/dt/grosv/sundial"></a>
    <a href="https://packagist.org/packages/grosv/sundial"><img alt="Latest Version" src="https://img.shields.io/packagist/v/grosv/sundial"></a>
    <a href="https://packagist.org/packages/grosv/sundial"><img alt="License" src="https://img.shields.io/packagist/l/grosv/sundial"></a>
</p>

### Installation

Warning: This package is not yet stable. There might be breaking changes until the 1.0 release.

```shell script
composer require grosv/sundial
```

### Usage

```php
$parser = new Grosv\Sundial\Parser();

$parser->parse('9th of August 2024 at 11:30 am')->toTimestamp(); // Get a unix timestamp

$parser->parse('December the 25th of this year')->toFormat('m/d/Y'); // 12/25/2020

// You can set a valid date range with setBetween($start, $end) where start and end are UNIX timestamps.
$parser->setBetween(time(), strtotime('+1 year'))->parse('tomorrow')->toFormat('m/d/Y'); // Works as expected
$parser->setBetween(time(), strtotime('+1 year'))->parse('August 9, 1992')->toFormat('m/d/Y'); // Exception 

// By default we use English but this can be overridden using the ISO 639-2 Code of the language if we have it.
$parser->setLanguage('esp')->parse('23 Mayo, 20204')->toFormat('m/d/Y'); // 05/23/2024
```

### Help Wanted

This is very much a minimum viable package and needs a great deal of TLC. Contributions are welcome from anyone
willing to jump in and work on the code, add more tests, knock out an issue, do a translation o a new language, 
or just build out the documentation.

### What Remains

Date and time parsing is in pretty good shape so as long as what's typed into the box contains an actual date and time 
it's usually parsed correctly. I'm sure we'll find cases where it's not and get those handled with little trouble.

The hard part starts now. Things like "Next Tuesday at 4 pm" and even better "Next Tuesday at 4 in the afternoon" and 
the gold standard would be "Christmas Eve at ten to midnight".

### Shout Out

This is the first package I've built using [Nuno Maduro's Skeleton PHP](https://github.com/nunomaduro/skeleton-php). 
It was a great experience. I'm definitely going to use it again and I wouldn't hesitate to recommend it to anyone
looking for a really solid PHP package boilerplate to kickstart their project.
