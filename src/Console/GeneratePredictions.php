<?php
declare(strict_types=1);

namespace Weather\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Weather\Fetcher;
use Weather\Fetcher\RandomPredictionGenerator;
use Weather\Prediction;
use Weather\PredictionStrategy;
use Weather\Repository;
use function array_unshift;
use function current;

final class GeneratePredictions extends Command
{
    protected static $defaultName = 'predictions:generate';

    private PredictionStrategy $predictionStrategy;
    private Fetcher $fetcher;

    public function __construct(PredictionStrategy $predictionStrategy, Fetcher $fetcher)
    {
        $this->predictionStrategy = $predictionStrategy;
        $this->fetcher = $fetcher;
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this->setDescription('Fetch and process weather data.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $repository = new Repository();
        $repository->connect();
        $repository->dropPredictionsTable();

        $io->writeln('Starting to fetch the predictions');

        $predictions = $this->fetcher
            ->fetch([]);
        $predictions = $this->predictionStrategy
            ->predict($predictions);
        $firstPrediction = current($predictions);
        $newPredictions = RandomPredictionGenerator::generatePredictionsFromPrediction($firstPrediction);

        array_unshift($newPredictions, $predictions);

        foreach ($newPredictions as $predictionsForTheDay) {
            foreach ($predictionsForTheDay as $prediction) {
                /** @var Prediction $prediction */
                $repository->addPrediction($prediction);
            }
        }

        $io->writeln(count($newPredictions) . ' predictions for the days were retrieved');

        return 0;
    }
}
