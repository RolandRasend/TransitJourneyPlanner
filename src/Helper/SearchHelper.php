<?php
declare(strict_types=1);

namespace App\Helper;

use App\Entity\JourneySearch;
use DateTime;
use DateTimeInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class SearchHelper
{
    private const string MOTIS_BASE_URL = 'https://europe.motis-project.de';
    private const string PLAN_ENDPOINT  = '/api/v1/plan';

    public function __construct(
        #[Autowire(service: 'monolog.logger.search_helper')] private readonly LoggerInterface $logger,
    ) {
    }

    public function search(JourneySearch $search): mixed
    {
        $client = new Client([
            'base_uri' => self::MOTIS_BASE_URL,
            'timeout'  => 20.0,
        ]);

        try {
            $response = $client->get(self::PLAN_ENDPOINT, [
                'headers' => [
                    'Accept'       => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'query' => [
                    'fromPlace' => 'de-DELFI_de:14713:8010205', // temporär debugging – Leipzig Hbf
                    'toPlace'   => 'at-Railway-Current-Reference-Data-2026_de:11000:900003200:1:51', // temporär debugging – Berlin Hbf
                    'time'      => new DateTime($search->getDepartureTime())->format(DateTimeInterface::ATOM),
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            $this->logger->error(
                'MOTIS API request failed',
                [
                    'file'      => $e->getFile(),
                    'code'      => $e->getCode(),
                    'message'   => $e->getMessage(),
                    'source'    => __METHOD__ . ' Z-' . __LINE__,
                ],
            );

            return false;
        }
    }

    public function prepareResult(array $raw): array
    {
        $tz  = new \DateTimeZone('Europe/Berlin');
        $leg = $raw['itineraries'][0]['legs'][0];

        $depTime = (new \DateTimeImmutable($leg['from']['departure']))->setTimezone($tz);
        $arrTime = (new \DateTimeImmutable($leg['to']['arrival']))->setTimezone($tz);

        $intermediateStops = $leg['intermediateStops'] ?? [];
        $stopCount         = count($intermediateStops);

        $stopsHtml = '';
        foreach ($intermediateStops as $stop) {
            $stopName = htmlspecialchars($stop['name'] ?? '');
            $stopArr  = (new \DateTimeImmutable($stop['arrival']))->setTimezone($tz);
            $stopDep  = (new \DateTimeImmutable($stop['departure']))->setTimezone($tz);
            $stopsHtml .= sprintf(
                '<li class="journey__stop">'
                . '<time class="journey__stop-arr" datetime="%s">%s</time>'
                . '<time class="journey__stop-dep" datetime="%s">%s</time>'
                . '<span class="journey__stop-name">%s</span>'
                . '</li>',
                $stopArr->format('Y-m-d\TH:i'), $stopArr->format('H:i'),
                $stopDep->format('Y-m-d\TH:i'), $stopDep->format('H:i'),
                $stopName
            );
        }

        return [
            'fromName'     => htmlspecialchars($leg['from']['name'] ?? ''),
            'toName'       => htmlspecialchars($leg['to']['name'] ?? ''),
            'depFormatted' => $depTime->format('H:i'),
            'arrFormatted' => $arrTime->format('H:i'),
            'depAttr'      => $depTime->format('Y-m-d\TH:i'),
            'arrAttr'      => $arrTime->format('Y-m-d\TH:i'),
            'stopLabel'    => match($stopCount) {
                0       => 'Keine Zwischenhalte',
                1       => '1 Zwischenhalt',
                default => $stopCount . ' Zwischenhalte',
            },
            'stopsHtml'    => $stopsHtml,
        ];
    }
}
