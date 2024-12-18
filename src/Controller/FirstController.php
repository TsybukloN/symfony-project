<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FirstController extends AbstractController
{
    #[Route('/testway', 'testway-index')]
    public function test(): Response
    {
        return new Response(
            '<html><body>HelloWorld</body></html>'
        );
    }
}
