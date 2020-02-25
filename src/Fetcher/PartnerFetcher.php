<?php
declare(strict_types=1);

namespace Weather\Fetcher;

use Weather\Fetcher;

final class PartnerFetcher implements Fetcher
{
    /** @var Fetcher[] */
    private array $fetchers;

    public function __construct(Fetcher ...$fetchers)
    {
        $this->fetchers = $fetchers;
    }

    /** @inheritDoc */
    public function fetch(array $allPredictions): array
    {
        $predictions = [];
        foreach ($this->fetchers as $fetcher) {
            $predictions = $fetcher->fetch($predictions);
        }

        return $predictions;
    }
}
