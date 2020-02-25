<?php

namespace Weather\Tests\Fetcher;

use DateTime;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Weather\Fetcher;
use Weather\Fetcher\Parser\XmlParser;
use Weather\Fetcher\XmlPartnerPredictionFetcher;
use Weather\Parser;
use Weather\Prediction;
use Weather\Temperature;

class XmlPartnerPredictionFetcherTest extends TestCase
{
    private Fetcher $fetcher;
    /** @var Parser&MockObject */
    private Parser $parser;

    protected function setUp(): void
    {
        $this->parser = $this->createMock(XmlParser::class);
        $this->fetcher = new XmlPartnerPredictionFetcher($this->parser);
    }

    public function testCanFetchData(): void
    {
        $prediction = new Prediction('location', new DateTime(), Temperature::createFromCelsius(10));
        $this->parser->expects(self::once())
            ->method('parse')
            ->willReturn([$prediction]);

        self::assertNotEmpty($this->fetcher->fetch([]));
    }
}
