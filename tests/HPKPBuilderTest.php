<?php

use Bepsvpt\HPKPBuilder\HPKPBuilder;
use PHPUnit\Framework\TestCase;

class HPKPBuilderTest extends TestCase
{
    public function test_hpkp()
    {
        $config = [
            'hashes' => [
                ['algo' => 'sha256', 'hash' => 'YLh1dUR9y6Kja30RrAn7JKnbQG/uEtLMkBgFF2Fuihg='],
            ],

            'include-sub-domains' => false,

            'max-age' => 15552000,

            'report-only' => false,

            'report-uri' => null,
        ];

        $header = (new HPKPBuilder($config))->getHeaderArray();

        $this->assertSame(
            'pin-sha256="YLh1dUR9y6Kja30RrAn7JKnbQG/uEtLMkBgFF2Fuihg="; max-age=15552000',
            $header['Public-Key-Pins']
        );
    }

    public function test_report_only()
    {
        $config = [
            'hashes' => [
                ['algo' => 'sha256', 'hash' => 'YLh1dUR9y6Kja30RrAn7JKnbQG/uEtLMkBgFF2Fuihg='],
            ],

            'include-sub-domains' => true,

            'max-age' => 15552000,

            'report-only' => true,

            'report-uri' => 'https://example.com',
        ];

        $header = (new HPKPBuilder($config))->getHeaderArray();

        $this->assertSame(
            'pin-sha256="YLh1dUR9y6Kja30RrAn7JKnbQG/uEtLMkBgFF2Fuihg="; max-age=15552000; includeSubDomains; report-uri="https://example.com"',
            $header['Public-Key-Pins-Report-Only']
        );
    }
}
