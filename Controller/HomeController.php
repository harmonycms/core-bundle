<?php

namespace Harmony\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 *
 * @package Harmony\Bundle\CoreBundle\Controller
 */
class HomeController extends AbstractController
{

    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }
}