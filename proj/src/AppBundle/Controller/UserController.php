<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Users;
use Symfony\Component\Validator\Constraints\Date;
use \PDO;


class UserController extends Controller{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('index.html.twig');
    }
    /**
     * @Route("/addUser", name="addUser")
     **/
    public function addAction(){
        return $this->render('form.html.twig',array(
            'mode'=>'new',
            'msg'=>'',
            'email'=>null,
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
     * @Route("/login", name="login")
     */

    public function loginAction(Request $request){
        $email= $request->get('email');
        $query="SELECT * FROM users WHERE email='$email';";
        $em=$this->getDoctrine()->getEntityManager();
        $stmt = $em->getConnection()->prepare($query);
        $stmt->execute();
        $res=$stmt->fetchAll();
        if (sizeof($res)==0) return $this->render('Connection.html.twig', array('msg'=> 'Email does not exist'));
        foreach ($res as $r)$s=$r['email'];
        $password=sha1($request->get('password'));
        foreach ($res as $r)$s=$r['password'];
        if ($s!=$password) return $this->render('Connection.html.twig', array('msg'=> 'Wrong Password'));
        return $this->render('Connection.html.twig', array('msg'=> 'Connection Successful'));
    }
}

