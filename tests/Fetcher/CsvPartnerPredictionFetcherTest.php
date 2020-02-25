<?php

namespace Weather\Tests\Fetcher;

use DateTime;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Weather\Fetcher;
use Weather\Fetcher\CsvPartnerPredictionFetcher;
use Weather\Fetcher\Parser\CsvParser;
use Weather\Parser;
use Weather\Prediction;
use Weather\Temperature;

class CsvPartnerPredictionFetcherTest extends TestCase
{
    private Fetcher $fetcher;
    /** @var Parser&MockObject */
    private Parser $parser;

    protected function setUp(): void
    {
        $this->parser = $this->createMock(CsvParser::class);
        $this->fetcher = new CsvPartnerPredictionFetcher($this->parser);
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
