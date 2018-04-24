<?php

namespace Emse\PlateformBundle\Controller;

use Emse\PlateformBundle\Entity\Advert;
use Emse\PlateformBundle\Entity\Application;
use Emse\PlateformBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{
//    public function indexAction() {
//
//        $nom = 'm4r';
//        $prenom = 'thiz';
//
//        return $this->render('@EmsePlateform/Advert/index.html.twig', compact('nom', 'prenom'));
////        return new Response('Notre reponse sans style :-(');
//    }

    public function indexAction() {
//        if ($page < 1){
//            throw new notFoundHttpException('page ' . $page . ' not found');
//        }
        $em = $this->getDoctrine()->getManager();
        $adverts = $em->getRepository('Emse\PlateformBundle\Entity\Advert')->findAll();
        return $this->render('@EmsePlateform/Advert/index.html.twig', compact('page', 'adverts'));
    }

    public function menuAction($limit) {

        // On fixe en dur une liste ici, bien entendu par la suite
        // on la récupérera depuis la BDD !
        $listAdverts = array(
            array('id' => 2, 'title' => 'Recherche développeur Symfony'),
            array('id' => 5, 'title' => 'Mission de webmaster'),
            array('id' => 9, 'title' => 'Offre de stage webdesigner'),
        );

        return $this->render('@EmsePlateform/Advert/menu.html.twig', array(
                      'listAdverts' => $listAdverts,
        ));
    }


    public function viewAction($id, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $advert = $em->getRepository('Emse\PlateformBundle\Entity\Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        $listApplications = $em->getRepository('Emse\PlateformBundle\Entity\Application')->findBy(array('advert' => $advert));

        return $this->render('@EmsePlateform/Advert/view.html.twig', array(

            'advert' => $advert,
            'listApplications' => $listApplications

        ));
//        $session = $request->getSession();
//        $userId = $session->set('user_id', 19);
//
//        return $this->render('@EmsePlateform/Advert/view.html.twig', compact('id'));

    }

    public function addAction(Request $request) {


        $advert = new Advert();
        $advert->setTitle('Recherche développeur Symfony.');
        $advert->setAuthor('Alexandre');
        $advert->setContent("Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…");

        $image = new Image();
        $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
        $image->setAlt('Job de rêve');
        $advert->setImage($image);

// Création d'une première candidature

        $application1 = new Application();
        $application1->setAuthor('Marine');
        $application1->setContent("J'ai toutes les qualités requises.");

        $application2 = new Application();
        $application2->setAuthor('Pierre');
        $application2->setContent("Je suis très motivé.");

        $application1->setAdvert($advert);
        $application2->setAdvert($advert);

        $em = $this->getDoctrine()->getManager();
        $em->persist($advert);
        $em->persist($application1);
        $em->persist($application2);

        $em->flush();


        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
            return $this->redirectToRoute('advert_view', array('id' => $advert->getId()));
        }

        // Si on n'est pas en POST, alors on affiche le formulaire
        return $this->render('@EmsePlateform/Advert/add.html.twig', array('advert' => $advert));

    }


    public function editAction(Request $request) {

        if ($request->isMethod('POST')) {
            $session = $request->getSession();
            $session->getFlashbag()
                    ->add('info', 'modif ok');

            return $this->redirectToRoute('advert_view', array('id' => 5));
        }

        return $this->render('@EmsePlateform/Advert/edit.html.twig');
    }

    public function deleteAction(Request $request) {

        return $this->render('@EmsePlateform/Advert/delete.html.twig');
    }


    public function editImageAction($advertId) {
        $em = $this->getDoctrine()
                   ->getManager();

        $advert = $em->getRepository('Emse\PlateformBundle\Entity\Advert')
                     ->find($advertId);
        // On modifie l'URL de l'image par exemple
        $advert->getImage()
               ->setUrl('test.png');
        // On n'a pas besoin de persister l'annonce ni l'image.
        // Rappelez-vous, ces entités sont automatiquement persistées car
        // on les a récupérées depuis Doctrine lui-même
        // On déclenche la modification
        $em->flush();

        return new Response('OK');
    }
}


