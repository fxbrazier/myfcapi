<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\ClubStat;
//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use AdminBundle\Helper\Response\ApiResponse;
use Exception;


/**
 * Clubstat controller.
 *
 * @Route("api/clubstat")
 */
class ClubStatController extends JsonController
{
    /**
    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @Route("s", name="clubStatList")
     * @Method("POST")
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc (
     *
     *  description="ClubStats with orders, pagination and research",
     *  section="ClubStat",
     *
     *  parameters={
     *      {"name"="orders", "dataType"="array", "required"=false, "format"="[ ['label', 'asc'] ]"},
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
                    'AppBundle\Entity\ClubStat',
                    [
                        'id',
                        'label',
                    ]
                )
                ->getResults()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @Route("/new", name="ClubStatCreate")
     * @Method("POST")
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc(
     *     description="Creates a new clubStat entity",
     *     section="ClubStat",
     *     headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *          {"name"="label", "dataType"="string", "required"=true, "description"="ClubStat label"},
     *          {"name"="value", "dataType"="string", "required"=true, "description"="ClubStat value"},
     *          {"name"="shortLabel", "dataType"="string", "required"=true, "description"="ClubStat short label"},
     *          {"name"="club.name", "dataType"="string", "required"=true, "description"="ClubStat club"},
     *     }
     * )
     *
     */
    public function createAction(Request $request)
    {
        $json = $this->getJson($request)->toArray();

        $clubStat = new ClubStat();
        $form    = $this->createForm(ClubStatFormType::class, $clubStat);
        $form->submit($json);

        if (!$form->isValid()) {
            return new ApiResponse(null, 422, $this->getErrorMessages($form));
        } else {
            $em   = $this->getDoctrine()->getManager();
            $em->persist($clubStat);
            $em->flush();
            return new ApiResponse(
                $clubStat->serializeEntity()
            );
        }
    }

    /**
     * Get a clubStat by id
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc(
     *     description="get clubStat",
     *     section="ClubStat"
     * )
     *
     * @Route("/{id}", name="getClubStat")
     * @Method("GET")
     *
     */
    public function getAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:ClubStat');
        $clubStat = $repository->findOneById($request->get('id'));
        if (empty($clubStat)) {
            return new ApiResponse(null, 404, ['ClubStat not found']);
        } else {
            return new ApiResponse(
                $clubStat->serializeEntity()
            );
        }
    }

    /**
     * Edit a ClubStat
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc(
     *     description="Edit a clubStat",
     *     section="ClubStat",
     *     headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *          {"name"="label", "dataType"="string", "required"=true, "description"="ClubStat label"},
     *     }
     * )
     *
     * @Route("/{id}/edit", name="clubStatEdit")
     * @Method("POST")
     *
     */
    public function editAction(Request $request)
    {
        $json       = $this->getJson($request)->toArray();
        $em         = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:ClubStat');
        $clubStat = $repository->findOneById($request->get('id'));
        if (empty($clubStat)) {
            return new ApiResponse(null, 404);
        }
        $editForm = $this->createForm(ClubStatFormType::class, $clubStat);
        $editForm->submit($json);
        if ($editForm->isValid()) {
            $em->persist($clubStat);
            $em->flush();
            return new ApiResponse(
                $clubStat->serializeEntity()
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
     *     description="Delete existing ClubStat",
     *     section="ClubStat",
     *     headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     }
     * )
     *
     * @Route("/{id}/remove", name="clubStatRemove")
     * @Method("DELETE")
     *
     */
    public function removeAction(Request $request)
    {
        try {
            $em         = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('AppBundle:ClubStat');
            $clubStat = $repository->find($request->get('id'));
            if (!$clubStat) {
                throw $this->createNotFoundException('Unable to find ClubStat id.');
            }
            $em->remove($clubStat);
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
