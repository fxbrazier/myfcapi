<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Club;
use AdminBundle\Form\ClubType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use AdminBundle\Helper\Response\ApiResponse;
use Exception;

//
//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\HttpFoundation\Response;
//use AdminBundle\Entity\AbstractEntity;
//use Symfony\Component\HttpFoundation\StreamedResponse;
//use AdminBundle\Controller\JsonController;

//use Doctrine\ORM\EntityManager;
//use Doctrine\ORM\EntityRepository;
//use Doctrine\ORM\Query;
//use Doctrine\ORM\Query\ResultSetMapping;
//use DoctrineExtensions\Query\Mysql\IfElse;
//



/**
 * Club controller.
 *
 * @Route("api/club")
 */
class ClubController extends JsonController
{
    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @Route("s", name="clubList")
     * @Method("POST")
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc (
     *
     *  description="Clubs with orders, pagination and research",
     *  section="Club",
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
                    'AppBundle\Entity\Club',
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
     * @Route("/new", name="club_create")
     * @Method("POST")
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc(
     *     description="Creates a new club",
     *     section="Club",
     *     headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *          {"name"="name", "dataType"="string", "required"=true, "description"="Club name"},
     *          {"name"="blason", "dataType"="string", "required"=true, "description"="Club blason"},
     *          {"name"="clubStats", "dataType"="string", "required"=true, "description"="Club stats"},
     *     }
     * )
     *
     */
    public function createAction(Request $request)
    {
        $json = $this->getJson($request)->toArray();

        $club = new Club();
        $form    = $this->createForm(ClubType::class, $club);
        $form->submit($json);

        if (!$form->isValid()) {
            return new ApiResponse(null, 422, $this->getErrorMessages($form));
        } else {
            $em   = $this->getDoctrine()->getManager();
            $em->persist($club);
            $em->flush();
            return new ApiResponse(
                $club->serializeEntity()
            );
        }
    }

    /**
     * Get a club by id
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     * @ApiDoc(
     *     description="get club",
     *     section="Club"
     * )
     *
     * @Route("/{id}", name="getClub")
     * @Method("GET")
     *
     */
    public function getAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Club');
        $club = $repository->findOneById($request->get('id'));
        if (empty($club)) {
            return new ApiResponse(null, 404, ['Club not found']);
        } else {
            return new ApiResponse(
                $club->serializeEntity()
            );
        }
    }

    /**
     * Edit a Club
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc(
     *     description="Edit a club",
     *     section="Club",
     *     headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *          {"name"="Name", "dataType"="string", "required"=true, "description"="Club name"},
     *     }
     * )
     *
     * @Route("/{id}/edit", name="club_edit")
     * @Method("POST")
     *
     */
    public function editAction(Request $request)
    {
        $json       = $this->getJson($request)->toArray();
        $em         = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Club');
        $club = $repository->findOneById($request->get('id'));
        if (empty($club)) {
            return new ApiResponse(null, 404);
        }
        $editForm = $this->createForm(ClubType::class, $club);
        $editForm->submit($json);
        if ($editForm->isValid()) {
            $em->persist($club);
            $em->flush();
            return new ApiResponse(
                $club->serializeEntity()
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
     *     description="Delete existing Club",
     *     section="Club",
     *     headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     }
     * )
     *
     * @Route("/{id}/remove", name="clubRemove")
     * @Method("DELETE")
     *
     */
    public function removeAction(Request $request)
    {
        try {
            $em         = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('AppBundle:Club');
            $club = $repository->find($request->get('id'));
            if (!$club) {
                throw $this->createNotFoundException('Unable to find Club id.');
            }
            $em->remove($club);
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
