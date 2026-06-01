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
}
