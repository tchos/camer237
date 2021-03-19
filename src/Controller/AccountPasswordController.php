<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountPasswordController extends AbstractController
{
    private $entitymanager;

    public function __construct (EntityManagerInterface $entityManager)
    {
        $this->entitymanager = $entityManager;
    }
    /**
     * @Route("/compte/modifier-password", name="account_password")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $notification = null;
        // Récupération de l'utilisateur connecté
        $user = $this->getUser();

        //Créationn du formulaire de modification du mot de passe
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        //Soumission et validation du formulaire
        if($form->isSubmitted() && $form->isValid())
        {
            // On récupère l'ancien mot de passe saisi dans le formulaire
            $old_pwd = $form->get('old_password')->getData();

            //On vérifie que le old_password est le même que celui enregistré en bd
            if($encoder->isPasswordValid($user, $old_pwd))
            {
                $new_password = $encoder->encodePassword($user, $form->get('new_password')->getData());
                $user->setPassword($new_password);

                // On persiste le user avec son new password en bd
                $this->entitymanager->persist($user);
                $this->entitymanager->flush();

                $notification = "Votre mot de passe a été modifié avec succès !";
                // Message de modification avec succès du mot de passe
                $this->addFlash(
                    'notice',
                    'Votre mot de passe a été modifié avec succès !'
                );

                return $this->redirectToRoute('account');
            }
            else {
                // Message flash: L'ancien mot de passe entré n'est pas le bon
                $notification = "Votre mot de passe actuel n'est pas le bon !";
                $this->addFlash(
                    'error',
                    'Veuillez renseigner le bon ancien mot de passe !'
                );
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
