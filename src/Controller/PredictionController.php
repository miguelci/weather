<?php
declare(strict_types=1);

namespace Weather\Controller;

use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Weather\Prediction;
use Weather\Repository;
use Weather\Temperature;
use function array_map;
use function in_array;

final class PredictionController
{
    private const AVAILABLE_SCALES = ['celsius', 'fahrenheit'];

    public function index(string $date, string $scale = Temperature::DEFAULT_SCALE): JsonResponse
    {
        if (! in_array($scale, self::AVAILABLE_SCALES, true)) {
            return new JsonResponse('Error', Response::HTTP_BAD_REQUEST);
        }

        try {
            $date = (new DateTime($date))->format('Y-m-d');
        } catch (Exception $e) {
            return new JsonResponse('Error', Response::HTTP_BAD_REQUEST);
        }

        $repository = new Repository();
        $predictionsFromDatabase = $repository->getAllPredictions($date);

        $predictions = array_map(static function (array $prediction) use ($scale) {
            return Prediction::createFromDatabase($prediction, $scale);
        }, (array) $predictionsFromDatabase);

        //return new JsonResponse(['predictions' => [(string) $predictions[0]]]);

        return new JsonResponse([
            'predictions' => array_map(static function (Prediction $prediction) {
                return (string) $prediction;
            }, $predictions),
        ]);
    }

    public function fixtures(): JsonResponse
    {
        $repository = new Repository();
        $dates = $repository->getAllPossibleDates();

        return new JsonResponse([
            'dates' => array_map(function (array $date) {
                return $date['date'];
            }, (array) $dates),
        ]);
    }
}
