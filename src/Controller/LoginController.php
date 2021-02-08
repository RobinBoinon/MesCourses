<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LoginController extends AbstractController
{
    /**
      * @Route("/", name="loginform")
    */
    public function LoginForm(Request $request): Response
    {
        $userrepository = $this->getDoctrine()->getRepository(User::class);

        //Form
        $user = new User();
        $form = $this->createFormBuilder($user)
            ->add('login', TextType::class, [
              'label' => 'Login',
              'attr' => array(
                  'placeholder' => 'Login'
              )
            ])
            ->add('password', TextType::class, [
              'label' => 'MDP',
              'attr' => array(
                  'placeholder' => 'Mot de passe'
              )
            ])
            ->add('connect', SubmitType::class, ['label' => 'Se connecter'])
            ->getForm();

        //Processing form
        $error = 0;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $datas = $form->getData();
            $user = $userrepository->findOneBy(['login' => $datas->getLogin()]);
            if(!is_null($user)){
              if(hash('sha512', ($user->getHashKey().$datas->getPassword())) == $user->getPassword()){
                $this->get('session')->set('UserID', $user->getId());
                return $this->redirectToRoute('my_list');
              }
              else{
                //error : invalid login/password
                $error = 1;
              }
            }
            else{
              //error : invalid login/password
              $error = 1;
            }
        }

        //Rendering
        return $this->render('login/loginform.html.twig', [
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }
}
