<?php

namespace AppBundle\Controller;

use AppBundle\Entity\LeagueResult;
use AdminBundle\Form\LeagueResultType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use AdminBundle\Helper\Response\ApiResponse;
use Exception;

/**
 * Leagueresult controller.
 *
 * @Route("leagueresult")
 */
class LeagueResultController extends JsonController
{
    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @Route("s", name="leagueResultList")
     * @Method("POST")
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc (
     *
     *  description="LeagueResults with orders, pagination and research",
     *  section="LeagueResult",
     *
     *  parameters={
     *      {"name"="orders", "dataType"="array", "required"=false, "format"="[ ['label', 'desc'] ]"},
     *      {"name"="page", "dataType"="integer", "required"=false, "description"="Page number (1 by default)"},
     *      {"name"="perPage", "dataType"="integer", "required"=true, "description"="Items per page (default -1)"},
     *      {"name"="search", "dataType"="string", "required"=false, "description"="Search on multiple columns"}
     *  }
     * )
     */
    public function listAction(Request $request)
    {
        return new ApiResponse(
            $this->get('fc5.entities_list_handler')
                ->handleList(
                    'AppBundle\Entity\LeagueResult',
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
     * @Route("/new", name="leagueResultCreate")
     * @Method("POST")
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc(
     *     description="Creates a new leagueResult",
     *     section="LeagueResult",
     *     headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *          {"name"="name", "dataType"="string", "required"=true, "description"="LeagueResult name"},
     *          {"name"="blason", "dataType"="string", "required"=true, "description"="LeagueResult blason"},
     *          {"name"="leagueResultStats", "dataType"="string", "required"=true, "description"="LeagueResult stats"},
     *     }
     * )
     *
     */
    public function createAction(Request $request)
    {
        $json = $this->getJson($request)->toArray();

        $leagueResult = new LeagueResult();
        $form    = $this->createForm(LeagueResultType::class, $leagueResult);
        $form->submit($json);

        if (!$form->isValid()) {
            return new ApiResponse(null, 422, $this->getErrorMessages($form));
        } else {
            $em   = $this->getDoctrine()->getManager();
            $em->persist($leagueResult);
            $em->flush();
            return new ApiResponse(
                $leagueResult->serializeEntity()
            );
        }
    }

    /**
     * Get a leagueResult by id
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     * @ApiDoc(
     *     description="get leagueResult",
     *     section="LeagueResult"
     * )
     *
     * @Route("/{id}", name="getLeagueResult")
     * @Method("GET")
     *
     */
    public function getAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:LeagueResult');
        $leagueResult = $repository->findOneById($request->get('id'));
        if (empty($leagueResult)) {
            return new ApiResponse(null, 404, ['LeagueResult not found']);
        } else {
            return new ApiResponse(
                $leagueResult->serializeEntity()
            );
        }
    }

    /**
     * Edit a LeagueResult
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc(
     *     description="Edit a leagueResult",
     *     section="LeagueResult",
     *     headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *          {"name"="Name", "dataType"="string", "required"=true, "description"="LeagueResult name"},
     *     }
     * )
     *
     * @Route("/{id}/edit", name="leagueResultEdit")
     * @Method("POST")
     *
     */
    public function editAction(Request $request)
    {
        $json       = $this->getJson($request)->toArray();
        $em         = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:LeagueResult');
        $leagueResult = $repository->findOneById($request->get('id'));
        if (empty($leagueResult)) {
            return new ApiResponse(null, 404);
        }
        $editForm = $this->createForm(LeagueResultType::class, $leagueResult);
        $editForm->submit($json);
        if ($editForm->isValid()) {
            $em->persist($leagueResult);
            $em->flush();
            return new ApiResponse(
                $leagueResult->serializeEntity()
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
     *     description="Delete existing LeagueResult",
     *     section="LeagueResult",
     *     headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     }
     * )
     *
     * @Route("/{id}/remove", name="leagueResultRemove")
     * @Method("DELETE")
     *
     */
    public function removeAction(Request $request)
    {
        try {
            $em         = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('AppBundle:LeagueResult');
            $leagueResult = $repository->find($request->get('id'));
            if (!$leagueResult) {
                throw $this->createNotFoundException('Unable to find LeagueResult id.');
            }
            $em->remove($leagueResult);
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
