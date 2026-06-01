<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\JourneySearch;
use App\Helper\SearchHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @author      robertthieme
 * @since       01.06.26
 */
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
        $origin = $query->get('origin');
        $destination = $query->get('destination');
        $departureTime = $query->get('departure_time');
        $journeySearch = new JourneySearch()->setDepartureTime($departureTime)->setOrigin($origin)->setDestination($destination);
        $searchResult = $this->searchHelper->search($journeySearch);
        return new Response();
    }
}
