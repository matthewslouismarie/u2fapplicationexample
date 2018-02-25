<?php

namespace App\Controller;

use App\Entity\U2fToken;
use App\Form\LoginForm;
use App\Form\U2fAuthenticationForm;
use App\Service\U2fServerService;
use App\Submission\LoginSubmission;
use App\Submission\U2fAuthenticationSubmission;
use Doctrine\ORM\EntityManagerInterface;
use Firehed\U2F\SignResponse;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SecurityController extends AbstractController
{
    /**
     * @Route(
     *  "/login",
     *  name="security_login")
     */
    public function login(Request $httpRequest)
    {
        $formSubmission = new LoginSubmission();
        $form = $this
            ->createForm(LoginForm::class, $formSubmission)
            ->add('submit', SubmitType::class)
        ;

        $form->handleRequest($httpRequest);
        if ($form->isSubmitted() && $form->isValid()) {
            $httpRequest
                ->getSession()
                ->set('credentials', [
                    'username' => $formSubmission->getUsername(),
                    'password' => $formSubmission->getPassword(),
                ])
            ;

            return new RedirectResponse($this->generateUrl('u2f_login'));
        }

        return $this->render('security/login.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route(
     *  "/u2f-authenticate",
     *  name="u2f_login")
     */
    public function u2fAuthenticate(
        EntityManagerInterface $em,
        Request $httpRequest,
        U2fServerService $u2fService)
    {
        $credentialsArray = $httpRequest
            ->getSession()
            ->get('credentials')
        ;
        $u2fRegistrations = $em
            ->getRepository(U2fToken::class)
            ->getMemberRegistrations($credentialsArray['username'])
        ;
        $u2fSignRequests = $u2fService
            ->getServer()
            ->generateSignRequests($u2fRegistrations)
        ;
        $u2fSubmission = new U2fAuthenticationSubmission();
        $form = $this
            ->createForm(U2fAuthenticationForm::class, $u2fSubmission)
            ->add('submit', SubmitType::class)
        ;

        $form->handleRequest($httpRequest);
        if ($form->isSubmitted() && $form->isValid()) {
            $u2fServer = $u2fService->getServer();
            $u2fServer
                ->setRegistrations($u2fRegistrations)
                ->setSignRequests($httpRequest
                    ->getSession()
                    ->get('u2fSignRequests'))
            ;
            $response = SignResponse::fromJson($u2fSubmission
                ->getU2fTokenResponse())
            ;
            $registration = $u2fServer->authenticate($response);

            $em
                ->getRepository(U2fToken::class)
                ->updateCounter($registration)
            ;

            $credentialsArray['checked_and_valid'] = true;
            $httpRequest
                ->getSession()
                ->set('credentials', $credentialsArray)
            ;

            return new RedirectResponse($this->generateUrl('process_login'));
        }

        $httpRequest
            ->getSession()
            ->set('u2fSignRequests', $u2fSignRequests)
        ;

        return $this->render('u2f_login.html.twig', [
            'form' => $form->createView(),
            'u2fSignRequests' => json_encode($u2fSignRequests),
        ]);
    }

    /**
     * @Route(
     *  "/process-login",
     *  name="process_login",
     *  methods={"GET"})
     */
    public function processLogin()
    {
    }

    /**
     * @Route(
     *  "/logout",
     *  name="security_logout")
     */
    public function logout()
    {
    }
}
