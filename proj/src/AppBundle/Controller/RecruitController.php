<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Entity\Recruit;

class RecruitController extends Controller
{
    /**
     * @Route("/Recruit", name="Recruit")
     **/
    public function recruitAction(Request $request,Session $session){
        if ($session->has('login')){
            $email=$session->get('login')->getEmail();
            $query="SELECT * FROM users WHERE email='$email';";
            $em=$this->getDoctrine()->getEntityManager();
            $stmt = $em->getConnection()->prepare($query);
            $stmt->execute();
            $res=$stmt->fetchAll();
            foreach ($res as $r){
                $name=$r['name'];
                $fname=$r['fname'];
                $birth=$r['birth'];
            }
            return $this->render("Recruit.html.twig",array(
                'email'=>$email,
                'name'=>$name,
                'fname'=>$fname,
                'birth'=>$birth,
                'cv'=>null,
                'phone'=>null
            ));


        }
        else return $this->render("Recruit.html.twig",array(
            'email'=>null,
            'name'=>null,
            'fname'=>null,
            'birth'=>null,
            'cv'=>null,
            'phone'=>null
        ));
    }/**
     * @Route("/RecruitConfirm", name="RecruitConfirm")
     **/
    public function recruitConfirm(Request $request,Session $session){
        $email=$request->get('email');
        $em=$this->getDoctrine()->getEntityManager();
        $user= new Recruit();
        $user->setEmail($email);
        $user->setName($request->get('name'));
        $user->setFName($request->get('fname'));
        $str=($request->get('birth'));
        $user->setBirth(date_create(date($str)));
        $user->setPhone($request->get('phone'));
        $user->setCv($request->get('cv'));
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('homepage');
    }


}
