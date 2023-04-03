<?php

namespace App\Controller;

use LDAP\Result;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoAppController
{
    #[Route('/')]
    public function homepage(): Response
    {
        return new Response('Un autre message de test');
    }

    #[Route('/browse/{slug}')]
    public function browse(string $slug = null): Response
    {
        if($slug)
        {
            $title = str_replace('-', ' ', $slug);
        }
        else 
        {
            $title = 'Fin du test';
        }
        
        return new Response('Message de test : '.$title);
    }
}