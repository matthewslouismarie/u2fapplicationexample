<?php

namespace App\Controller;

use App\Form\PasswordForm;
use App\Submission\PasswordSubmission;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserSettingsController extends AbstractController
{
    /**
     * @Route(
     *  "/settings",
     *  name="settings")
     */
    public function settings(
        EntityManagerInterface $em,
        Request $request,
        UserPasswordEncoderInterface $encoder)
    {
        $submission = new PasswordSubmission();
        $form = $this
            ->createForm(PasswordForm::class, $submission)
            ->add('submit', SubmitType::class)
        ;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $member = $this->getUser();
            var_dump($member);
            var_dump($submission->getPassword());
            $newPassword = $encoder
                ->encodePassword($member, $submission->getPassword())
            ;
            $member->setPassword($newPassword);
            $em->persist($member);
            $em->flush();
        }

        return $this->render('settings.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
