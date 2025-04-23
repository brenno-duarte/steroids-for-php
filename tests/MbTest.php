<?php

use PHPUnit\Framework\TestCase;

class MbTest extends TestCase
{
    public function testMBFunctions()
    {
        $this->assertTrue(mb_str_contains(
            hex2bin('e6bca2e5ad97e381afe383a6e3838be382b3e383bce38389'), 
            hex2bin('e383bc'))
        );

        $this->assertEquals(
            "A very|long|wooooooo|ooord.", 
            mb_wordwrap("A very long woooooooooord.", 8, "|", true)
        );

        mb_preg_match_all("|<[^>]+>(.*)</[^>]+>|U",
        "<b>example: </b><div align=left>this is a test</div>",
        $out, PREG_PATTERN_ORDER);

        $this->assertEquals("<b>example: </b>, <div align=left>this is a test</div>", $out[0][0] . ", " . $out[0][1]);
        $this->assertEquals("example: , this is a test", $out[1][0] . ", " . $out[1][1]);

        $this->assertEquals(
            "There are 5 monkeys in the trée", 
            mb_sprintf("There are %d monkeys in the %s", 5, "trée")
        );

        $this->assertEquals(
            "1988-08-01", 
            mb_vsprintf("%04d-%02d-%02d", explode('-', '1988-8-1'))
        );

        $this->assertEquals("Åäö", mb_ucwords('åäö'));
        $this->assertEquals("öäå", mb_strrev('åäö'));
        $this->assertEquals("æøå  ", mb_str_pad('æøå', 5));
        $this->assertEquals("Helo, wrd!", mb_count_chars('Hello, world!', 3));
        $this->assertEquals(0, mb_strcasecmp('Daníel', 'DANÍEL'));
        $this->assertEquals("éggxs", mb_substr_replace('éggs', 'x', -1));
        
    }
}