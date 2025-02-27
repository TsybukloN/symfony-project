<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class ErrorController extends AbstractController
{
    #[Route('/error', name: 'error')]
    public function __invoke(Request $request, Throwable $exception): Response
    {
        if ($request->get('message') && $request->get('statusCode')) {
            $message = $request->get('message');
            $statusCode = $request->get('statusCode');
        } else {
            if ($exception instanceof HttpExceptionInterface) {
                $statusCode = $exception->getStatusCode();
                $message = $exception->getMessage();
            } else {
                $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                $message = 'An unexpected error occurred';
            }
        }

        return $this->render('error/error.html.twig', [
            'message' => $message,
            'code' => $statusCode
        ]);
    }
}
