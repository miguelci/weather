<?php

namespace Weather\Tests\Fetcher\Parser;

use DateTime;
use PHPStan\Testing\TestCase;
use Weather\Fetcher\Parser\JsonParser;
use Weather\Parser;
use Weather\Prediction;
use Weather\Temperature;
use function file_get_contents;

class JsonParserTest extends TestCase
{
    private Parser $parser;

    protected function setUp(): void
    {
        $this->parser = new JsonParser();
    }

    public function testItCanParseAJsonFile(): void
    {
        /** @var string $content */
        $content = file_get_contents(__DIR__ . '/../../../src/Fetcher/samples/temps.json');
        $predictions = $this->parser->parse($content, []);

        self::assertNotEmpty($predictions);
        self::assertCount(11, $predictions);
        $firstPrediction = array_shift($predictions)[0];

        self::assertInstanceOf(Prediction::class, $firstPrediction);
        self::assertSame('Amsterdam', $firstPrediction->getLocation());
        self::assertSame('2018-01-12 00:00:00', $firstPrediction->getDateTime()->format('Y-m-d H:i:s'));
        self::assertSame(-0.56, $firstPrediction->getTemperature()->getValue());
    }

    public function testItCanParseAJsonFileAndAddPreviousDates(): void
    {
        /** @var string $content */
        $content = file_get_contents(__DIR__ . '/../../../src/Fetcher/samples/temps.json');
        $dateTime = new DateTime('2018-01-12 00:00:00');
        $key = $dateTime->format(Parser::DATE_FORMAT);

        $predictions = $this->parser->parse($content, [
            $key => [
                new Prediction(
                    'Amsterdam',
                    new DateTime('2018-01-12 00:00:00'),
                    Temperature::createFromCelsius(4)
                ),
            ],
        ]);

        self::assertNotEmpty($predictions);
        self::assertCount(11, $predictions);
        self::assertCount(2, $predictions[$key]);
    }
}
