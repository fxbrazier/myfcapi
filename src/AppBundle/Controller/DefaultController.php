<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
    * @Route("/login", name="login")
    */
    public function loginAction(Request $request)
    {
        //var_dump($request);die;
        /*$user = $this->getUser();

        if ($user instanceof UserInterface) {
            return $this->redirectToRoute('homepage');
        }*/

        /** @var AuthenticationException $exception */
        //$exception = $this->get('security.authentication_utils')->getLastAuthenticationError();

        //return $this->render('default/login.html.twig', [
          //'error' => $exception ? $exception->getMessage() : NULL,
        //]);

        $error = $this->get('security.authentication_utils')
            ->getLastAuthenticationError();

        return new JsonResponse($error);
    }

    /**
     * @Route("/profil", name="my_profil")
     */
    public function profilAction(Request $request)
    {

        //infos de l'utlisateur
        $user = $this->get('security.token_storage')->getToken()->getUser();

        return new JsonResponse($user);

    }
}
