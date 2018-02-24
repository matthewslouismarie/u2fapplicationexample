<?php

namespace App\Controller;

use App\Form\PasswordForm;
use App\Submission\PasswordSubmission;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;

class UserSettingsController extends AbstractController
{
    /**
     * @Route(
     *  "/settings",
     *  name="settings")
     */
    public function settings()
    {
        $submission = new PasswordSubmission();
        $form = $this
            ->createForm(PasswordForm::class, $submission)
            ->add('submit', SubmitType::class)
        ;

        return $this->render('settings.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
