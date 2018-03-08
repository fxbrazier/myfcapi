<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use AppBundle\Form\Type\UserType;
use AppBundle\Entity\User;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class UserController extends Controller
{
    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"user"})
     * @Rest\Post("/createUser")
     *
     * @ApiDoc(
     *     description="Registration",
     *     section="User",
     *     parameters={
     *          {"name"="firstname", "dataType"="string", "required"=true, "description"="User firstname"},
     *          {"name"="lastname", "dataType"="string", "required"=true, "description"="User lastname"},
     *          {"name"="email", "dataType"="string", "required"=true, "description"="User email"},
     *          {"name"="pseudonym", "dataType"="string", "required"=true, "description"="User pseudonym"},
     *          {"name"="plainPassword", "dataType"="password", "required"=true, "description"="User password"},
     *     }
     * )
     */
    public function postUsersAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, ['validation_groups'=>['Default', 'New']]);

        $form->submit($request->request->all());
        //dump($request->request->all());die;

        if ($form->isValid()) {
            $encoder = $this->get('security.password_encoder');

            // le mot de passe en claire est encodé avant la sauvegarde
            $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($encoded);

            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($user);
            $em->flush();
            //return new JsonResponse(['message' => "Your account has been saved"]);
            return $user;

        } else {
            return $form;
        }
    }

     /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Put("/users/{id}")
     *
     * @ApiDoc(
     *     description="Edit a user",
     *     section="User",
     *      headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *          {"name"="firstname", "dataType"="string", "required"=true, "description"="User firstname"},
     *          {"name"="lastname", "dataType"="string", "required"=true, "description"="User lastname"},
     *          {"name"="email", "dataType"="string", "required"=true, "description"="User email"},
     *          {"name"="pseudonym", "dataType"="string", "required"=true, "description"="User pseudonym"},
     *          {"name"="plainPassword", "dataType"="password", "required"=true, "description"="User password"},
     *     }
     * )
     */
    public function updateUserAction(Request $request)
    {
        return $this->updateUser($request, true);
    }

    /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Patch("/users/{id}")
     */
    public function patchUserAction(Request $request)
    {
        return $this->updateUser($request, false);
    }

    private function updateUser(Request $request, $clearMissing)
    {
        $user = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:User')
                ->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire
        /* @var $user User */

        if (empty($user)) {
            return $this->userNotFound();
        }

        if ($clearMissing) { // Si une mise à jour complète, le mot de passe doit être validé
            $options = ['validation_groups'=>['Default', 'FullUpdate']];
        } else {
            $options = []; // Le groupe de validation par défaut de Symfony est Default
        }

        $form = $this->createForm(UserType::class, $user, $options);

        $form->submit($request->request->all()/*, $clearMissing*/);
        //var_dump($request->getContent());die;

        if ($form->isValid()) {
            // Si l'utilisateur veut changer son mot de passe
            if (!empty($user->getPlainPassword())) {
                $encoder = $this->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($encoded);
            }
            $em = $this->get('doctrine.orm.entity_manager');
            $em->merge($user);
            $em->flush();
            return $user;
        } else {
            return $form;
        }
    }

    private function userNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('User not found');
    }

    /**
     * @Route("/usersList", name="users_list")
     * @Method({"GET"})
     *
     * @ApiDoc(
     *     description="Users list",
     *     section="User",
     *      headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     },
     * )
     */
    public function getUsersAction(Request $request)
    {
        $users = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:User')
                ->findAll();
        /* @var $users User[] */

        $formatted = [];
        foreach ($users as $user) {
            $formatted[] = [
               'id' => $user->getId(),
               'firstname' => $user->getFirstname(),
               'lastname' => $user->getLastname(),
               'pseudonym' => $user->getPseudonym(),
               'email' => $user->getEmail(),
            ];
        }

        return new JsonResponse($formatted);
    }
}