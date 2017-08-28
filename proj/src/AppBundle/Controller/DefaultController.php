<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Entity\Users;
use Symfony\Component\Validator\Constraints\Date;

class DefaultController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     **/
    public function adminAction(Session $session){
        if($session->has('login')){
            $login=$session->get('login');
            $email= $login->getEmail();
            $password= $login->getPassword();
            if ($login->getEmail()=='digitalize@admin.com' && $login->getPassword()=='7d5c37b2b576b733eeefcbfa4d6498c4d4623d21'){
                return $this->render('Admin.html.twig',array('msg'=>'connected'));
            }return $this->render('Admin.html.twig',array('msg'=>'not connected'));
        }return $this->render('Admin.html.twig',array('msg'=>'not connected'));
    }

}
