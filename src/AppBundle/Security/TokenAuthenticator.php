<?php

namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\RouterInterface;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private $em;
    
    /**
    * @var \Symfony\Component\Routing\RouterInterface
    */
    private $router;

    /**
     * Default message for authentication failure.
     *
     * @var string
     */
    private $failMessage = 'Invalid credentials';

    public function __construct(EntityManager $em, RouterInterface $router)
    {
        $this->em = $em;
        $this->router = $router;
    }

    public function getCredentials(Request $request)
    {
        /*if ($request->getPathInfo() != '/login' || !$request->isMethod('POST')) {
            return;
        }*/
        //var_dump($request->request);die;
        return [
            'username' => $request->request->get('_username'),
            'password' => $request->request->get('_password')
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        //var_dump($credentials);die;
        $username = $credentials['username'];

        //var_dump($username);die;

        return $this->em->getRepository('AppBundle:User')->findOneBy(['email' => $username]);

        //var_dump($user);die;

    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        //var_dump($credentials);die;
        if ($credentials['password'] != 'fc5api') {
            return;
        }

        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        //$request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);

        //$url = $this->router->generate('login');
        //return new RedirectResponse($url);

        return new JsonResponse(array('message' => $exception->getMessageKey()), Response::HTTP_FORBIDDEN);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        //$url = $this->router->generate('homepage');
        //return new RedirectResponse($url);

        return new JsonResponse('Success');

    }

    public function supportsRememberMe()
    {
        return true;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse('Authorization header required', 401);
    }
}