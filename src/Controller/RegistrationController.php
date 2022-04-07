<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class RegistrationController extends AbstractController
{
    /**
     * @Route("/reg", name="app_reg")
     */
    public function registration(Request $request, UserPasswordHasherInterface $passwordhasher, ManagerRegistry $doctrine, ValidatorInterface $validatorInterface): Response
    {
        $regForm = $this->createFormBuilder()
        ->add('username', TextType::class, [
            'label' => 'Employee',
        ])
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'required' => true,
            'first_options' => ['label' => 'Password'],
            'second_options' => ['label' => 'Repear Password'],
        ])
        ->add('Register', SubmitType::class)
        ->getForm();

        // new to get request and to submit the form in database
        $regForm->handleRequest($request);
        if($regForm->isSubmitted()){
            $getform = $regForm->getData();

            $user = new User();
            $user->setUsername($getform['username']);

            // Validation with the username
            $user->setnamevalidation($getform['username']);
            $user->setPassword(
                $passwordhasher->hashPassword($user, $getform['password'])
            );

            // here we set validation before submitting
            $user->setRawPassword($getform['password']);
            $errors = $validatorInterface->validate($user);
            if(count($errors) > 0)
            {
                return $this->render('registration/index.html.twig', [
                    'regForm' => $regForm->createView(),
                    'errors' => $errors
                ]);
            }
            else{
                 // now store in the database, need entity manager
            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            }
            // before redirect make a addFlash message
            $this->addFlash('usersuccess', 'Successfully Login');
            // redirect after save the data in the database
            return $this->redirect($this->generateUrl('app_home'));
            

        }

        // pass this form to the twig template
        return $this->render('registration/index.html.twig', [
            'regForm' => $regForm->createView(),
            'errors' => null // of not pass it will give error bcz we did validation above         
        ]);
    }
}
