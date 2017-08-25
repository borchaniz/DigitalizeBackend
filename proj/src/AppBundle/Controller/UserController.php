<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Users;
use AppBundle\Login;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Constraints\Date;


class UserController extends Controller{
    /**
     * @Route("/addUser", name="addUser")
     **/
    public function addAction(Request $request){
        return $this->render('form.html.twig',array(
            'mode'=>'new',
            'msg'=>'',
            'email'=>$request->get('email'),
            'fname'=>null,
            'birth'=>null,
            'name'=>null
            ));
    }
    /**
     * @Route("/editUser", name="editUser")
     **/
    public function editUserAction(Session $session){
        if($session->has('login')){
            $login=$session->get('login');
            $email= $login->getEmail();
            $password= $login->getPassword();
            $query="SELECT * FROM users WHERE email='$email';";
            $em=$this->getDoctrine()->getEntityManager();
            $stmt = $em->getConnection()->prepare($query);
            $stmt->execute();
            $res=$stmt->fetchAll();
            if (sizeof($res)==0)  return $this->render('Connection.html.twig',array('msg'=>"Vous devez etre connectés pour modifier votre Compte"));
            foreach ($res as $r)$s=$r['password'];
            if ($s!=$password)  return $this->render('Connection.html.twig',array('msg'=>"Vous devez etre connectés pour modifier votre Compte"));
            foreach ($res as $r){
                $name= $r['name'];
                $fname= $r['fname'];
                $email= $r['email'];
                $birth = $r['birth'];
            }
            return $this->render('form.html.twig', array(
                'mode'=>'edit',
                'msg'=> null,
                'name'=>$name,
                'fname'=>$fname,
                'email'=>$email,
                'birth'=>$birth
            ));
        }
        return $this->render('Connection.html.twig',array('msg'=>"Vous devez etre connectés pour modifier votre Compte"));
    }
    /**
     * @Route("/add", name="add")
     **/
    public function newUserAction(Request $request){
        $email=$request->get('email');
        if ($email==null)  return $this->render('form.html.twig',array(
            'mode'=>'new',
            'msg'=>"Tapez votre Email SVP",
            'email'=>$request->get('email'),
            'name'=>$request->get('name'),
            'fname'=>$request->get('fname'),
            'birth'=>$request->get('birth')
        ));
        $query="SELECT email FROM users WHERE email='$email';";
        $em=$this->getDoctrine()->getEntityManager();
        $stmt = $em->getConnection()->prepare($query);
        $stmt->execute();
        $res=$stmt->fetchAll();
        if (sizeof($res)>0) return $this->render('form.html.twig',array('mode'=>'new',
            'msg'=>"Ce mail est dejà associé à un compte",
            'email'=>'',
            'name'=>$request->get('name'),
            'fname'=>$request->get('fname'),
            'birth'=>$request->get('birth')
            ));
        $user= new Users();
        $user->setEmail($email);
        $str= $request->get('password');
        if ($str==null)  return $this->render('form.html.twig',array('mode'=>'new',
            'msg'=>"Tapez un mot de passe SVP",
            'email'=>$request->get('email'),
            'name'=>$request->get('name'),
            'fname'=>$request->get('fname'),
            'birth'=>$request->get('birth')
        ));
        $str= sha1($str);
        $user->setPassword($str);
        $str=$request->get('name');
        if ($str==null)  return $this->render('form.html.twig',array('mode'=>'new',
            'msg'=>"Tapez votre nom SVP",
            'email'=>$request->get('email'),
            'name'=>$request->get('name'),
            'fname'=>$request->get('fname'),
            'birth'=>$request->get('birth')
        ));
        $user->setName($str);
        $str=$request->get('fname');
        if ($str==null)  return $this->render('form.html.twig',array('mode'=>'new',
            'msg'=>"Tapez votre Prénom SVP",
            'email'=>$request->get('email'),
            'name'=>$request->get('name'),
            'fname'=>$request->get('fname'),
            'birth'=>$request->get('birth')
            ));
        $user->setFname($str);
        $str=$request->get('birth');
        if ($str==null)  return $this->render('form.html.twig',array('mode'=>'new',
            'msg'=>"Tapez votre Date de naissance SVP",
            'email'=>$request->get('email'),
            'name'=>$request->get('name'),
            'fname'=>$request->get('fname'),
            'birth'=>$request->get('birth')
        ));
        $user->setBirth(date_create(date($str)));
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('homepage');
    }
    /**
     * @Route("/edit", name="edit")
     **/
    public function editAction(Request $request, Session $session){
        $newemail=$request->get('email');
        $login=$session->get('login');
        $oldemail=$login->getEmail();
        $query="SELECT * FROM users WHERE email='$newemail';";
        $em=$this->getDoctrine()->getEntityManager();
        $stmt = $em->getConnection()->prepare($query);
        $stmt->execute();
        $res=$stmt->fetchAll();
        if ($newemail==null)$newemail=$oldemail;
        else $newemail=$request->get('email');
        if ($newemail!=$oldemail && sizeof($res)>0) return $this->render('form.html.twig',array('mode'=>'edit',
            'msg'=>"Cet email est déja associé à un autre compte",
            'email'=>'',
            'name'=>$request->get('name'),
            'fname'=>$request->get('fname'),
            'birth'=>$request->get('birth')
        ));
        $user= new Users();
        $user->setEmail($newemail);
        $str= $request->get('password');
        if ($str==null)  $password=$login->getPassword();
        else if ($request->get('newpassword')==null){
            return $this->render('form.html.twig',array('mode'=>'edit',
                'msg'=>"Tapez votre nouveau Mot de Passe!",
                'email'=>$request->get('email'),
                'name'=>$request->get('name'),
                'fname'=>$request->get('fname'),
                'birth'=>$request->get('birth')
            ));
        }else $password=sha1($request->get('newpassword'));
        $user->setPassword($password);
        $name=$request->get('name');
        if ($name==null){
            foreach ($res as $r)$name=$r['name'];
        }
        $user->setName($name);
        $fname=$request->get('fname');
        if ($fname==null){
            foreach ($res as $r)$fname=$r['fname'];
        }
        $user->setFname($fname);
        $birth=$request->get('birth');
        if ($birth==null){
            foreach ($res as $r)$birth=$r['birth'];
        }
        $user->setBirth($birth);
        $query="UPDATE users SET email='$newemail', password='$password', name='$name', fname='$fname', birth='$birth' WHERE email='$oldemail' ;";
        $em=$this->getDoctrine()->getEntityManager();
        $stmt = $em->getConnection()->prepare($query);
        $stmt->execute();
        $session->clear();
        return $this->render('index.html.twig',array('msg'=>'edited'));
    }
    /**
     * @Route("/", name="homepage")
     */
    public function loginAction(Request $request, Session $session){
        if($session->has('login')){
            $login=$session->get('login');
            $email= $login->getEmail();
            $password= $login->getPassword();
            $query="SELECT * FROM users WHERE email='$email';";
            $em=$this->getDoctrine()->getEntityManager();
            $stmt = $em->getConnection()->prepare($query);
            $stmt->execute();
            $res=$stmt->fetchAll();
            if (sizeof($res)==0) return $this->render('index.html.twig', array('msg'=> null));
            foreach ($res as $r)$s=$r['password'];
            if ($s!=$password) return $this->render('index.html.twig', array('msg'=> 'password'));
            foreach ($res as $r){
                $name= $r['name'];
                $fname= $r['fname'];
                $email= $r['email'];
            }
            return $this->render('index.html.twig', array(
                'msg'=> 'connected',
                'name'=>$name,
                'fname'=>$fname,
                'email'=>$email
            ));
        }else if ($request->getMethod()=='POST'){

            $email= $request->get('email');
            $query="SELECT * FROM users WHERE email='$email';";
            $em=$this->getDoctrine()->getEntityManager();
            $stmt = $em->getConnection()->prepare($query);
            $stmt->execute();
            $res=$stmt->fetchAll();
            if (sizeof($res)==0) return $this->render('index.html.twig', array('msg'=> 'email'));
            foreach ($res as $r)$s=$r['email'];
            $password=sha1($request->get('password'));
            foreach ($res as $r)$s=$r['password'];
            if ($s!=$password) return $this->render('index.html.twig', array('msg'=> 'password'));
            if ($request->get('remember')=='true'){
                $login=new Login();
                $login->setEmail($request->get('email'));
                $login->setPassword($password);
                $session->set('login', $login);
            }
            foreach ($res as $r){
                $name= $r['name'];
                $fname= $r['fname'];
                $email= $r['email'];
            }
            return $this->render('index.html.twig', array(
                'msg'=> 'connected',
                'name'=>$name,
                'fname'=>$fname,
                'email'=>$email
            ));
        }else return $this->render('index.html.twig', array('msg'=> null));
    }
    /**
     * @Route("/logout", name="logout")
     **/
    public function logoutAction(Request $request,Session $session){
        $session->clear();
        return $this->render("index.html.twig",array('msg'=>'disconnected'));
    }
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
    }

    /**
     * @Route("/mail", name="mail")
     **/
    public function mailAction(Request $request){
        mail("nadhem.m.2016@ieee.org",$request->get('sub'),+$request->get('mail'));
        return $this->render('index.html.twig', array('msg'=> "mail"));
    }

}

