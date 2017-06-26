<?php

namespace PiiDev\PortfolioBundle\Controller;

use PiiDev\PortfolioBundle\Entity\Commandes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PanelController extends Controller
{
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('PortfolioBundle:Commandes');
        $attentes = $repository->findBy(array('status' => '0'));
        $cours = $repository->findBy(array('status' => '1'));
        $terminee = $repository->findBy(array('status' => '2'));

        return $this->render('PortfolioBundle:Panel:index.html.twig', array(
          'attentes' => count($attentes),
          'cours' => count($cours),
          'terminee' => count($terminee) + 1,
          'perc' => (count($terminee) + 1)*20,
        ));
    }
}
