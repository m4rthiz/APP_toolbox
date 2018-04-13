<?php

namespace Emse\PlateformBundle\Controller;

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
        return $this->render('@EmsePlateform/Advert/index.html.twig', compact('page'));
    }

    public function viewAction($id, Request $request) {
        $session = $request->getSession();
        $userId = $session->set('user_id', 19);

        return $this->render('@EmsePlateform/Advert/view.html.twig', compact('id'));

    }

    public function addAction(Request $request) {

        if ($request->isMethod('POST')) {
            $session = $request->getSession();
            $session->getFlashbag()
                    ->add('info', 'ok ok ok');

            return $this->redirectToRoute('advert_view', array('id' => 5));
        }

        return $this->render('@EmsePlateform/Advert/add.html.twig');
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

//    public function viewSlugAction($year, $slug, $format) {
//
//
//        return new Response('l\'année : ' . $year . '</br> le slug : ' . $slug . '</br> format : ' . $format);
//    }

}


