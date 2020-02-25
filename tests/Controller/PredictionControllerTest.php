<?php
declare(strict_types=1);

namespace Weather\Tests\Controller;

use DateTime;
use PHPStan\Testing\TestCase;
use Weather\Controller\PredictionController;
use Weather\Prediction;
use Weather\Repository;
use Weather\Temperature;
use function json_decode;
use const JSON_THROW_ON_ERROR;

final class PredictionControllerTest extends TestCase
{
    private Repository $repository;
    private const DATE = '2018-01-01';

    protected function setUp(): void
    {
        $this->repository = new Repository();
        $this->repository->connect();
        $this->repository->addPrediction(
            new Prediction('Amsterdam', new DateTime(self::DATE), Temperature::createFromCelsius(12))
        );
    }

    public function testIndex(): void
    {
        $result = (new PredictionController())->index(self::DATE);

        $predictions = json_decode((string) $result->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertNotEmpty($predictions);
        self::assertCount(1, $predictions['predictions']);
        $prediction = json_decode((string) $predictions['predictions'][0], true, 512, JSON_THROW_ON_ERROR);
        self::assertSame('Amsterdam', $prediction['location']);
        self::assertSame('2018-01-01 00:00:00', $prediction['dateTime']);
        self::assertSame('12 ºC', $prediction['temperature']);
    }

    public function testFixtures(): void
    {
        $result = (new PredictionController())->fixtures();

        $content = json_decode((string) $result->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertSame(self::DATE, $content['dates'][0]);
    }

    protected function tearDown(): void
    {
        $this->repository->dropPredictionsTable();
    }
}
