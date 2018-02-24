<?php

namespace App\Controller;

use App\Form\RegistrationForm;
use App\Submission\RegistrationSubmission;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    /**
     * @Route(
     *  "/register",
     *  name="registration")
     */
    public function register()
    {
        $submission = new RegistrationSubmission();
        $form = $this
            ->createForm(RegistrationForm::class, $submission)
            ->add('submit', SubmitType::class)
        ;

        return $this->render('registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
