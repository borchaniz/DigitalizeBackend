<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Entity\RDV;

class RDVController extends Controller
{
    /**
     * @Route("/RDV", name="RDV")
     **/
    public function rdvAction(Request $request,Session $session){

            $email=$session->get('login')->getEmail();
            $query="SELECT * FROM users WHERE email='$email';";
            $em=$this->getDoctrine()->getEntityManager();
            $stmt = $em->getConnection()->prepare($query);
            $stmt->execute();
            $res=$stmt->fetchAll();
            foreach ($res as $r){
                $name=$r['name'];
                $fname=$r['fname'];
            }
            return $this->render("RDV.html.twig",array(
                'email'=>$email,
                'name'=>$name,
                'fname'=>$fname,
                'suj'=>null,
                'des'=>null,
                'phone'=>null,
                'rdvdate'=>null,
                'rdvtime'=>null
            ));
    }
    /**
    * @Route("/RDVConfirm", name="RDVConfirm")
    **/
    public function rdvConfirm(Request $request,Session $session){
        $email=$request->get('email');
        $em=$this->getDoctrine()->getEntityManager();
        $user= new RDV();
        $user->setEmail($email);
        $user->setName($request->get('name'));
        $user->setFName($request->get('fname'));
        $str=($request->get('rdvdate'));
        $user->setRDVDate(date_create(date($str)));
        $str=($request->get('rdvtime'));
        $user->setRDVTime(date_create(date($str)));
        $user->setPhone($request->get('phone'));
        $user->setSuj($request->get('suj'));
        $user->setDes($request->get('des'));
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('homepage');
    }
    /**
     * @Route("/RDVList", name="RDVList")
     **/
    public function rdvList(Request $request,Session $session){
        $query="SELECT * FROM rdv;";
        $em=$this->getDoctrine()->getEntityManager();
        $stmt = $em->getConnection()->prepare($query);
        $stmt->execute();
        $res=$stmt->fetchAll();
        return $this->render('RDVList.html.twig',array ('data'=>$res));
    }

}
