<?php

namespace HchatBundle\Controller;

use HchatBundle\Entity\Chat;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\Serializer\SerializerBuilder;

class ChatController extends Controller
{
    /**
     * @Route("/Chat",name="chat_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine();
        $users = $em->getRepository("AppBundle:User")->createQueryBuilder("e")->getQuery()->getResult();
        return $this->render('HchatBundle:Chat:index.html.twig', array('users' => $users));
    }
    /**
     *
     * @Route("/Getmessage/{id}", name="Get_message", options={"expose"=true})
     *
     * @param $id
     * @return JsonResponse
     */
    public function GetMessageByUser($id)
    {

        $iduser=$this->getUser()->getId();
        $em = $this->getDoctrine();
        $Result=$em->getRepository("HchatBundle:Chat")->createQueryBuilder("e")
            //->select("e.id,t.id userId,t.username,e.message,e.readed,e.creationDate,t.gender,t.imagelink")
            ->select("e.id,e.message,e.readed,e.creationDate,t.id")
            ->innerJoin("e.receiver","r")
            ->innerJoin("e.transmitter","t")
            ->where("e.transmitter = :idtransmitter and e.receiver = :idreceiver ")
            ->orWhere("e.transmitter = :idreceiver  and e.receiver = :idtransmitter ")
            ->setParameter("idtransmitter",$iduser)
            ->setParameter("idreceiver",$id)
        ;
        $users=$Result->getQuery()->getResult();
        $serializer = SerializerBuilder::create()->build();
        $response = $serializer->serialize($users,'json');
        return new  JsonResponse($response);
    }

}
