<?php

namespace PiiDev\PortfolioBundle\Controller;

use PiiDev\PortfolioBundle\Entity\Commandes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PanelCommandesController extends Controller
{
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('PortfolioBundle:Commandes');
        $commandes = $repository->findBy(array(), array('status' => 'asc'));

        return $this->render('PortfolioBundle:Panel:commandes.html.twig', array(
          'commandes' => $commandes,
        ));
    }

    public function removeAction($id)
    {

      $repository = $this->getDoctrine()->getManager()->getRepository('PortfolioBundle:Commandes');
      $commande = $repository->find($id);

      $em = $this->getDoctrine()->getEntityManager();
      $em->remove($commande);
      $em->flush();

      return $this->redirectToRoute('portfolio_panel_commandes');
    }

    public function statusAction($id, $status)
    {
      $repository = $this->getDoctrine()->getManager()->getRepository('PortfolioBundle:Commandes');
      $commande = $repository->find($id);

      $commande->setStatus($status);

      $em = $this->getDoctrine()->getEntityManager();
      $em->persist($commande);
      $em->flush();

      return $this->redirectToRoute('portfolio_panel_commandes');
    }
}
