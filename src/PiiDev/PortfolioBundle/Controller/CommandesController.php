<?php

namespace PiiDev\PortfolioBundle\Controller;

use PiiDev\PortfolioBundle\Entity\Commandes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;

class CommandesController extends Controller
{
    public function indexAction(Request $request, $titre)
    {
        $commande = new Commandes();
        switch ($titre) {
          case 'P':
            $commande->setTitre('Développement d\'un plugin');
            break;

          case 'WEB':
            $commande->setTitre('Développement d\'un site internet');
            break;

          case 'VPS':
            $commande->setTitre('Mise en place d\'un serveur VPS');
            break;

          case 'MC':
            $commande->setTitre('Mise en place d\'un serveur Minecraft');
            break;

          default:
            return $this->redirectToRoute('portfolio_index');
            break;
        }

        $formBuillder = $this->get('form.factory')->createBuilder(FormType::class, $commande);

        $formBuillder
          ->add('nom', TextType::class, array('label' => 'Nom d\'utilisateur :'))
          ->add('email', EmailType::class, array('label' => 'Adresse email :'))
          ->add('titre', TextType::class, array('disabled' => true, 'label' => 'Service souhaité :'))
          ->add('description', TextareaType::class, array('label' => 'Description de votre projet :'))
          ->add('save', SubmitType::class, array('label' => 'Commander mon service', 'attr' => array('class' => 'btn btn-default btn-lg btn-block')))
        ;

        $form = $formBuillder->getForm();

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
          $em = $this->getDoctrine()->getManager();
          $em->persist($commande);
          $em->flush();

          //return $this->redirectToRoute('portfolio_comingsoon');
          return $this->render('PortfolioBundle:Commandes:finish.html.twig', array(
            'commande' => $commande
          ));
        }

        return $this->render('PortfolioBundle:Commandes:index.html.twig', array(
          'form' => $form->createView(),
        ));
    }
}
