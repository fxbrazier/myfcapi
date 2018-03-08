<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Friend;
use AdminBundle\Form\FriendType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use AdminBundle\Helper\Response\ApiResponse;
use Exception;


/**
 * Friend controller.
 *
 * @Route("api/friend")
 */
class FriendController extends JsonController
{
    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @Route("s", name="friendList")
     * @Method("POST")
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc (
     *
     *  description="Friends with orders, pagination and research",
     *  section="Friend",
     *
     *  parameters={
     *      {"name"="orders", "dataType"="array", "required"=false, "format"="[ ['name', 'desc'] ]"},
     *      {"name"="page", "dataType"="integer", "required"=false, "description"="Page number (1 by default)"},
     *      {"name"="perPage", "dataType"="integer", "required"=false, "description"="Items per page"},
     *      {"name"="search", "dataType"="string", "required"=false, "description"="Search on multiple columns"}
     *  }
     * )
     */
    public function listAction(Request $request)
    {
        return new ApiResponse(
            $this->get('fc5.entities_list_handler')
                ->handleList(
                    'AppBundle\Entity\Friend',
                    [
                        'id',
                        'name',
                    ]
                )
                ->getResults()
        );
    }


    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @Route("/new", name="friend_create")
     * @Method("POST")
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc(
     *     description="Creates a new friend",
     *     section="Friend",
     *     headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *          {"name"="name", "dataType"="string", "required"=true, "description"="Friend name"},
     *          {"name"="blason", "dataType"="string", "required"=true, "description"="Friend blason"},
     *          {"name"="friendStats", "dataType"="string", "required"=true, "description"="Friend stats"},
     *     }
     * )
     *
     */
    public function createAction(Request $request)
    {
        $json = $this->getJson($request)->toArray();

        $friend = new Friend();
        $form    = $this->createForm(FriendType::class, $friend);
        $form->submit($json);

        if (!$form->isValid()) {
            return new ApiResponse(null, 422, $this->getErrorMessages($form));
        } else {
            $em   = $this->getDoctrine()->getManager();
            $em->persist($friend);
            $em->flush();
            return new ApiResponse(
                $friend->serializeEntity()
            );
        }
    }

    /**
     * Get a friend by id
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     * @ApiDoc(
     *     description="get friend",
     *     section="Friend"
     * )
     *
     * @Route("/{id}", name="getFriend")
     * @Method("GET")
     *
     */
    public function getAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Friend');
        $friend = $repository->findOneById($request->get('id'));
        if (empty($friend)) {
            return new ApiResponse(null, 404, ['Friend not found']);
        } else {
            return new ApiResponse(
                $friend->serializeEntity()
            );
        }
    }


    /**
     * Edit a Friend
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc(
     *     description="Edit a friend",
     *     section="Friend",
     *     headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *          {"name"="Name", "dataType"="string", "required"=true, "description"="Friend name"},
     *     }
     * )
     *
     * @Route("/{id}/edit", name="friendEdit")
     * @Method("POST")
     *
     */
    public function editAction(Request $request)
    {
        $json       = $this->getJson($request)->toArray();
        $em         = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Friend');
        $friend = $repository->findOneById($request->get('id'));
        if (empty($friend)) {
            return new ApiResponse(null, 404);
        }
        $editForm = $this->createForm(FriendType::class, $friend);
        $editForm->submit($json);
        if ($editForm->isValid()) {
            $em->persist($friend);
            $em->flush();
            return new ApiResponse(
                $friend->serializeEntity()
            );
        } else {
            return new ApiResponse(null, 422, $this->getErrorMessages($editForm));
        }
    }




    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc(
     *     description="Delete existing Friend",
     *     section="Friend",
     *     headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     }
     * )
     *
     * @Route("/{id}/remove", name="friendRemove")
     * @Method("DELETE")
     *
     */
    public function removeAction(Request $request)
    {
        try {
            $em         = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('AppBundle:Friend');
            $friend = $repository->find($request->get('id'));
            if (!$friend) {
                throw $this->createNotFoundException('Unable to find Friend id.');
            }
            $em->remove($friend);
            $em->flush();
            return new ApiResponse(
                [
                    'success' => true,
                ]
            );
        } catch (Exception $e) {
            return new ApiResponse(null, 404, $e->getMessage());
        }
    }

}
