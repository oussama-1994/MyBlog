<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistartionType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription",name="security_registration")
     */
    public function registration(Request $request, ObjectManager $manager,UserPasswordEncoderInterface $encoder){
        $user=new User();
        $form=$this->createForm(RegistartionType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $hash=$encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('security_login');
        }






        return $this->render('security/registration.html.twig',[
            'form'=>$form->createView()
        ]);

    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/connexion",name="security_login")
     */


    public function login(){




        return $this->render('security/login.html.twig') ;


    }

    /**
     * @Route("/deconnexion",name="security_logout")
     */
public function logout(){}

}
