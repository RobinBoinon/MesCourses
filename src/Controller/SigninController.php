<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Lists;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SigninController extends AbstractController
{
    /**
      * @Route("/signin", name="signinform")
    */
    public function SigninForm(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userrepository = $this->getDoctrine()->getRepository(User::class);
        $listrepository = $this->getDoctrine()->getRepository(Lists::class);

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
            ->add('passwordconfirm', TextType::class, [
              'label' => 'MDP2',
              'mapped' => false,
              'attr' => array(
                  'placeholder' => 'Confirmez le Mot de passe'
              )
            ])
            ->add('signin', SubmitType::class, ['label' => 'S\'inscrire'])
            ->getForm();

        //Processing form
        $error = 0;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $datas = $form->getData();
            if($datas->getPassword() == $form['passwordconfirm']->getData()){

              //New user
              $possiblehash = array('&','?','!','$','.',';',':');
              $myhash = rand(0,6);
              $myhash = $possiblehash[$myhash];
              $datas->setHashKey($myhash);
              $datas->setPassword(hash('sha512', ($myhash.$datas->getPassword())));
              $entityManager->persist($datas);
              $entityManager->flush();

              //New list for new user
              $new_list = new Lists();
              $new_list->setDate(new \DateTime());
              $new_list->setUser($datas);
              $entityManager->persist($new_list);
              $entityManager->flush();

              return $this->redirectToRoute('loginform');

            }
            else{
              //error : 2 different passwords
              $error = 1;
            }
        }

        //Rendering
        return $this->render('signin/signinform.html.twig', [
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }
}
