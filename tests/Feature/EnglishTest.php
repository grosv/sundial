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
