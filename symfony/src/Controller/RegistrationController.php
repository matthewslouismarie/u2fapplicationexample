<?php

namespace App\Controller;

use App\Entity\Member;
use App\Form\RegistrationForm;
use App\Repository\MemberRepository;
use App\Submission\RegistrationSubmission;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    /**
     * @Route(
     *  "/register",
     *  name="registration")
     */
    public function register(MemberRepository $mr, Request $request)
    {
        $submission = new RegistrationSubmission();
        $form = $this
            ->createForm(RegistrationForm::class, $submission)
            ->add('submit', SubmitType::class)
        ;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $member = new Member(null, $submission->getUsername());
            $mr->save($member, $submission->getPassword());
        }

        return $this->render('registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
