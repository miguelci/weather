<?php

namespace Weather\Tests\Fetcher;

use DateTime;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Weather\Fetcher;
use Weather\Fetcher\JsonPartnerPredictionFetcher;
use Weather\Fetcher\Parser\JsonParser;
use Weather\Parser;
use Weather\Prediction;
use Weather\Temperature;

class JsonPartnerPredictionFetcherTest extends TestCase
{
    private Fetcher $fetcher;
    /** @var Parser&MockObject */
    private Parser $parser;

    protected function setUp(): void
    {
        $this->parser = $this->createMock(JsonParser::class);
        $this->fetcher = new JsonPartnerPredictionFetcher($this->parser);
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
