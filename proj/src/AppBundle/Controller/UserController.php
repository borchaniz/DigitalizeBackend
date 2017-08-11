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
     * @Route("/", name="homepage")
     */

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

    public function editAction(){
        return $this->render('form.html.twig',array(
            'mode'=>'edit',
            'msg'=>"Tapez votre Email SVP",
            'email'=>$request->get('email'),
            'name'=>$request->get('name'),
            'fname'=>$request->get('fname'),
            'birth'=>$request->get('birth')
        ));
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
            if (sizeof($res)==0) return $this->render('form.html.twig',array('mode'=>'new',
                'msg'=>"Tapez votre Date de naissance SVP",
                'email'=>$request->get('email'),
                'name'=>$request->get('name'),
                'fname'=>$request->get('fname'),
                'birth'=>$request->get('birth')
            ));
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
        return $this->redirectToRoute("homepage");
    }
}

