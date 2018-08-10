<?php

namespace HchatBundle\Controller;

use HchatBundle\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        //Get All users
        $users=$this->getDoctrine()->getRepository("AppBundle:User")->createQueryBuilder("e")->getQuery()->getResult();
        return $this->render('HchatBundle:Default:index.html.twig',array('users'=>$users));
    }

     /**
     *
     * @Route("/SentNotif/{idreceiver}", name="Sent_Notif", options={"expose"=true})

     *
     * @param $idr
     * @return JsonResponse
     */
    public function SentMessageAction($idreceiver)
    {
        $em=$this->getDoctrine()->getManager();
        $receiver=$em->getRepository("AppBundle:User")->find($idreceiver);
        $notfcation=new Notification();
        $notfcation->setTransmitter($this->getUser());
        $notfcation->setReceiver($receiver);
        $em->persist($notfcation);
        $em->flush();
        return new JsonResponse(1);
    }
}
