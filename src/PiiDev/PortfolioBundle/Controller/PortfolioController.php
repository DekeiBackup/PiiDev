<?php

namespace PiiDev\PortfolioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PortfolioController extends Controller
{
    public function indexAction()
    {
        return $this->render('PortfolioBundle:Portfolio:index.html.twig');
    }
}
