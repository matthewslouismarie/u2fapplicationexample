<?php

namespace App\Controller;

use App\Entity\Member;
use App\Entity\U2fToken;
use App\Form\RegistrationForm;
use App\Repository\MemberRepository;
use App\Service\U2fServerService;
use App\Submission\RegistrationSubmission;
use Doctrine\ORM\EntityManagerInterface;
use Firehed\U2F\RegisterResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @todo Remove from the session afterwards.
     *
     * @Route(
     *  "/register",
     *  name="registration")
     */
    public function getRegister(
        EntityManagerInterface $em,
        MemberRepository $mr,
        Request $request,
        U2fServerService $u2fServerService,
        UserPasswordEncoderInterface $encoder)
    {
        $u2fServer = $u2fServerService->getServer();

        $submission = new RegistrationSubmission();
        $form = $this
            ->createForm(RegistrationForm::class, $submission)
            ->add('submit', SubmitType::class)
        ;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $u2fRegisterRequest = $request
                ->getSession()
                ->get('u2fRegisterRequest')
            ;
            $u2fServer->setRegisterRequest($u2fRegisterRequest);
            $u2fResponse = RegisterResponse::fromJson($submission->getU2fResponse());
            $u2fRegistration = $u2fServer->register($u2fResponse);
            $member = new Member(null, $submission->getUsername());
            $member->setPassword($encoder->encodePassword($member, $submission->getPassword()));
            $u2fToken = new U2fToken(
                null,
                base64_encode($u2fRegistration->getAttestationCertificateBinary()),
                $u2fRegistration->getCounter(),
                base64_encode($u2fRegistration->getKeyHandleBinary()),
                $member,
                base64_encode($u2fRegistration->getPublicKey())
            );
            $em->persist($member);
            $em->persist($u2fToken);
            $em->flush();
        } else {
            $u2fRegisterRequest = $u2fServer->generateRegisterRequest();
            $request
                ->getSession()
                ->set('u2fRegisterRequest', $u2fRegisterRequest)
            ;
    
            return $this->render('registration.html.twig', [
                'form' => $form->createView(),
                'u2fRegisterRequest' => json_encode($u2fRegisterRequest),
                'u2fSignRequests' => json_encode([]),
            ]);
        }
    }
}
