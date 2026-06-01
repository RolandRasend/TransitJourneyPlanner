<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @author      robertthieme
 * @since       01.06.26
 */
class SearchController
{
    #[Route('/journey/search')]
    public function search(Request $request): Response
    {
        $test = $request->getContent();
        var_dump(['blubb' => ['blubb' => $test]]);
        xdebug_info();
        return new Response('<html><body>ahhh</body></html>');
    }
}
