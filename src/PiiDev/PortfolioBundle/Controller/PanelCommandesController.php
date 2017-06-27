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

      $mailer = $this->get('mailer');

      $message = $mailer->createMessage()
        ->setSubject('Suivi de votre commande')
        ->setFrom('piidev.contact@gmail.com')
        ->setTo($commande->getEmail())
        ->setBody(
          $this->renderView(
              'Emails/email.html.twig',
              array('nom' => $commande->getNom(), 'titre' => 'Annulation de votre commande', 'id' => $id)
          ),
          'text/html'
        );

      $em = $this->getDoctrine()->getEntityManager();
      $em->remove($commande);
      $em->flush();

      $mailer->send($message);

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

      $mailer = $this->get('mailer');

      $titre = "";

      if($status == 1){
        $titre = 'Prise en charge de votre commande';
      }else {
        $titre = 'Livraison de votre commande';
      }

      $message = $mailer->createMessage()
        ->setSubject('Suivi de votre commande')
        ->setFrom('piidev.contact@gmail.com')
        ->setTo($commande->getEmail())
        ->setBody(
          $this->renderView(
              'Emails/email.html.twig',
              array('nom' => $commande->getNom(), 'titre' => $titre, 'id' => $id)
          ),
          'text/html'
        );

      $mailer->send($message);

      return $this->redirectToRoute('portfolio_panel_commandes');
    }
}
