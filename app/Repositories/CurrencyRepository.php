<?php

namespace App\Repositories;

use App\Contracts\Repositories\CurrencyRepositoryInterface;
use App\Models\Currency;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class CurrencyRepository extends AbstractRepository implements CurrencyRepositoryInterface
{
    /**
     * The currency model.
     *
     * @var \App\Models\Currency
     */
    protected $model = Currency::class;

    /**
     * Get converted value.
     *
     * @param string $baseCurrencyIso
     * @param string $targetCurrencyIso
     * @param int $amount
     * @return int
     */
    public function convert(string $baseCurrencyIso, string $targetCurrencyIso, int $amount): int
    {
        $response = (new Client())->get("https://www.valutafx.com/{$baseCurrencyIso}-{$targetCurrencyIso}.htm");

        $result = (new Crawler($response->getBody()->__toString()))->filter('.converter-result > .rate-value')->first()->text();

        $rate = str_replace(',', '', $result);

        return (int)round($amount * $rate);
    }
}
