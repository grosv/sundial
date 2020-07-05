<?php

use Grosv\Sundial\DateTimeOutOfRangeException;
use Grosv\Sundial\NoDateOrTimeStringFoundException;
use Grosv\Sundial\Parser;

test('failures to the front', function () {
    assertSame(true, true); // Just a placeholder test that makes it easy to dd() when things fail.
});

test('now as timestamp', function () {
    assertSame(time(), (new Parser())->parse('now')->toTimestamp());
});

test('now as mdY', function () {
    assertSame(date('m/d/Y'), (new Parser())->parse('now')->toFormat('m/d/Y'));
});

test('years work', function () {
    assertSame('2026', (new Parser())->parse('July 11, 2026')->toFormat('Y'));
    assertSame('1989', (new Parser())->parse('9/12/1989 at 4pm')->toFormat('Y'));
    assertSame('2019', (new Parser())->parse('2019-04-23')->toFormat('Y'));
});

test('months work', function () {
    assertSame('March', (new Parser())->parse('March 23, 2012')->toFormat('F'));
    assertSame('February', (new Parser())->parse('Feb 23, 2012')->toFormat('F'));
    assertSame('September', (new Parser())->parse('9/12/1989 at 4pm')->toFormat('F'));
});

test('dates work', function () {
    assertSame('23', (new Parser())->parse('March 23, 2012')->toFormat('d'));
    assertSame('11', (new Parser())->parse('Feb 11, 2012')->toFormat('d'));
    assertSame('12', (new Parser())->parse('9/12/1989 at 4pm')->toFormat('d'));
});

test('can get date from any date containing string', function () {
    assertSame('2020-04-20', (new Parser())->parse('April 20, 2020')->toFormat('Y-m-d'));
    assertSame('1974-08-13', (new Parser())->parse('13th of August 1974')->toFormat('Y-m-d'));
});

test('can find the time', function () {
    assertSame('11:20 PM', (new Parser())->parse('April 20, 2020 at 23:20')->toFormat('h:i A'));
    assertSame('07:15 AM', (new Parser())->parse('April 20, 2020 at 07:15')->toFormat('h:i A'));
    assertSame('07:45 PM', (new Parser())->parse('April 20, 2020 at 07:45pm')->toFormat('h:i A'));
    assertSame('08:51 PM', (new Parser())->parse('April 20, 2020 at 8:51 pm')->toFormat('h:i A'));
    assertSame('04:00 PM', (new Parser())->parse('April 20, 2020 at 4 pm')->toFormat('h:i A'));
    assertSame('02:00 AM', (new Parser())->parse('April 20, 2020 at 2am')->toFormat('h:i A'));
});

test('it can manage yesterday and tomorrow', function () {
    assertSame((new DateTime('12:00 am'))->add(new DateInterval('P1D'))->format('c'),
        (new Parser())->parse('tomorrow')->toFormat('c'));
    assertSame((new DateTime('12:00 am'))->sub(new DateInterval('P1D'))->format('c'),
        (new Parser())->parse('yesterday')->toFormat('c'));
    assertSame((new DateTime('14:00'))->add(new DateInterval('P1D'))->format('c'),
        (new Parser())->parse('2:00 pm tomorrow')->toFormat('c'));
    assertSame((new DateTime('16:00'))->sub(new DateInterval('P1D'))->format('c'),
        (new Parser())->parse('yesterday at 4:00 pm')->toFormat('c'));
});

it('throws an exception if earlier than boundary minimum', function () {
    (new Parser())->parse('April 20, 1992')->setBetween(time(), strtotime('tomorrow'))->toFormat('Y-m-d');
})->throws(DateTimeOutOfRangeException::class);

it('throws an exception if later than boundary minimum', function () {
    (new Parser())->parse('April 20, 2032')->setBetween(time(), strtotime('tomorrow'))->toFormat('Y-m-d');
})->throws(DateTimeOutOfRangeException::class);

it('throws an exception if timestamp earlier than boundary minimum', function () {
    (new Parser())->parse('April 20, 1992')->setBetween(time(), strtotime('tomorrow'))->toTimestamp();
})->throws(DateTimeOutOfRangeException::class);

it('throws an exception if timestamp later than boundary minimum', function () {
    (new Parser())->parse('April 20, 2032')->setBetween(time(), strtotime('tomorrow'))->toTimestamp();
})->throws(DateTimeOutOfRangeException::class);

it('throws an exception if the string is not date or time related', function () {
    (new Parser())->parse('Taco salad')->toTimestamp();
})->throws(NoDateOrTimeStringFoundException::class);
