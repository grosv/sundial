<?php

use Grosv\Sundial\Parser;

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
