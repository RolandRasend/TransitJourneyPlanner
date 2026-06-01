<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\JourneySearch;
use App\Helper\SearchHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SearchController
{
    public function __construct(
        private readonly SearchHelper $searchHelper
    ) {
    }

    #[Route('/journey/search')]
    public function search(Request $request): Response
    {
        $query = $request->query;
        $journeySearch = new JourneySearch();
        $journeySearch
            ->setDepartureTime($query->get('departure_time'))
            ->setOrigin($query->get('origin'))
            ->setDestination($query->get('destination'));

        $searchResult = $this->searchHelper->search($journeySearch);

        if (empty($searchResult['itineraries'])) {
            return new Response('<p>Keine Verbindung gefunden.</p>', Response::HTTP_OK, ['Content-Type' => 'text/html']);
        }

        $data     = $this->searchHelper->prepareResult($searchResult);
        $template = file_get_contents(__DIR__ . '/../../public/html/journey-result.html');

        $html = str_replace(
            ['{{fromName}}', '{{toName}}', '{{depAttr}}', '{{depFormatted}}', '{{arrAttr}}', '{{arrFormatted}}', '{{stopLabel}}', '{{stopsHtml}}'],
            [$data['fromName'], $data['toName'], $data['depAttr'], $data['depFormatted'], $data['arrAttr'], $data['arrFormatted'], $data['stopLabel'], $data['stopsHtml']],
            $template
        );

        return new Response($html, Response::HTTP_OK, ['Content-Type' => 'text/html; charset=UTF-8']);
    }
}
