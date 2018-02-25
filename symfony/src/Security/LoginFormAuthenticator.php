<?php

namespace App\Security;

use App\Entity\Member;
use App\Form\LoginForm;
use App\Submission\LoginSubmission;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    private $formFactory;

    private $om;

    private $router;

    private $encoder;

    public function __construct(
        FormFactoryInterface $formFactory,
        ObjectManager $om,
        RouterInterface $router,
        UserPasswordEncoderInterface $encoder)
    {
        $this->formFactory = $formFactory;
        $this->om = $om;
        $this->router = $router;
        $this->encoder = $encoder;
    }

    public function getCredentials(Request $request)
    {
        $credentials = $request
            ->getSession()
            ->get('credentials')
        ;

        if (!is_array($credentials)) {
            throw new AuthenticationException();
        } else {
            return $credentials;
        }
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $user = $this
            ->om
            ->getRepository(Member::class)->findOneBy(array(
                'username' => $credentials['username'],
            ));

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true === $credentials['checked_and_valid'];
    }

    protected function getLoginUrl()
    {
        return $this->router->generate('security_login');
    }

    /**
     * @todo Redirect to previously visited page.
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse($this->router->generate('home'));
    }

    public function supports(Request $request): bool
    {
        $route = $request
            ->attributes
            ->get('_route')
        ;
        $isRouteCorrect = 'process_login' === $route;
        $isMethodCorrect = $request->isMethod('GET');

        return $isRouteCorrect && $isMethodCorrect;
    }
}
