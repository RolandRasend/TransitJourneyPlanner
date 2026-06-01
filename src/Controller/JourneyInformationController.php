<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class JourneyInformationController
{
    #[Route('')]
    public function index(): Response
    {
        $html = file_get_contents(__DIR__ . '/../../public/html/journey-search.html');

        return new Response($html);
    }
}
