<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Match;
use AdminBundle\Form\MatchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use AdminBundle\Helper\Response\ApiResponse;
use Exception;

/**
 * Match controller.
 *
 * @Route("api/match")
 */
class MatchController extends JsonController
{
    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @Route("s", name="matchList")
     * @Method("POST")
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc (
     *
     *  description="Matchs with orders, pagination and research",
     *  section="Match",
     *
     *  parameters={
     *      {"name"="orders", "dataType"="array", "required"=false, "format"="[ ['name', 'desc'] ]"},
     *      {"name"="page", "dataType"="integer", "required"=false, "description"="Page number (1 by default)"},
     *      {"name"="perPage", "dataType"="integer", "required"=true, "description"="Items per page send if you want all of them -1"},
     *      {"name"="search", "dataType"="string", "required"=false, "description"="Search on multiple columns"}
     *  }
     * )
     */
    public function listAction(Request $request)
    {
        return new ApiResponse(
            $this->get('fc5.entities_list_handler')
                ->handleList(
                    'AppBundle\Entity\Match',
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
     * @Route("/new", name="match_create")
     * @Method("POST")
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc(
     *     description="Creates a new match",
     *     section="Match",
     *     headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *          {"name"="name", "dataType"="string", "required"=true, "description"="Match name"},
     *          {"name"="blason", "dataType"="string", "required"=true, "description"="Match blason"},
     *          {"name"="matchStats", "dataType"="string", "required"=true, "description"="Match stats"},
     *     }
     * )
     *
     */
    public function createAction(Request $request)
    {
        $json = $this->getJson($request)->toArray();

        $match = new Match();
        $form    = $this->createForm(MatchType::class, $match);
        $form->submit($json);

        if (!$form->isValid()) {
            return new ApiResponse(null, 422, $this->getErrorMessages($form));
        } else {
            $em   = $this->getDoctrine()->getManager();
            $em->persist($match);
            $em->flush();
            return new ApiResponse(
                $match->serializeEntity()
            );
        }
    }

    /**
     * Get a match by id
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     * @ApiDoc(
     *     description="get match",
     *     section="Match"
     * )
     *
     * @Route("/{id}", name="getMatch")
     * @Method("GET")
     *
     */
    public function getAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Match');
        $match = $repository->findOneById($request->get('id'));
        if (empty($match)) {
            return new ApiResponse(null, 404, ['Match not found']);
        } else {
            return new ApiResponse(
                $match->serializeEntity()
            );
        }
    }

    /**
     * Edit a Match
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc(
     *     description="Edit a match",
     *     section="Match",
     *     headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *          {"name"="Name", "dataType"="string", "required"=true, "description"="Match name"},
     *     }
     * )
     *
     * @Route("/{id}/edit", name="match_edit")
     * @Method("POST")
     *
     */
    public function editAction(Request $request)
    {
        $json       = $this->getJson($request)->toArray();
        $em         = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Match');
        $match = $repository->findOneById($request->get('id'));
        if (empty($match)) {
            return new ApiResponse(null, 404);
        }
        $editForm = $this->createForm(MatchType::class, $match);
        $editForm->submit($json);
        if ($editForm->isValid()) {
            $em->persist($match);
            $em->flush();
            return new ApiResponse(
                $match->serializeEntity()
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
     *     description="Delete existing Match",
     *     section="Match",
     *     headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     }
     * )
     *
     * @Route("/{id}/remove", name="matchRemove")
     * @Method("DELETE")
     *
     */
    public function removeAction(Request $request)
    {
        try {
            $em         = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('AppBundle:Match');
            $match = $repository->find($request->get('id'));
            if (!$match) {
                throw $this->createNotFoundException('Unable to find Match id.');
            }
            $em->remove($match);
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
