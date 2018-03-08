<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\JerseyType;
use AdminBundle\Form\JerseyTypeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use AdminBundle\Helper\Response\ApiResponse;
use Exception;


/**
 * Jerseytype controller.
 *
 * @Route("api/jerseytype")
 */
class JerseyTypeController extends JsonController
{
    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @Route("s", name="jerseyTypeList")
     * @Method("POST")
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc (
     *
     *  description="JerseyTypes with orders, pagination and research",
     *  section="JerseyType",
     *
     *  parameters={
     *      {"name"="orders", "dataType"="array", "required"=false, "format"="[ ['name', 'desc'] ]"},
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
                    'AppBundle\Entity\JerseyType',
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
     * @Route("/new", name="jerseyType_create")
     * @Method("POST")
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc(
     *     description="Creates a new jerseyType",
     *     section="JerseyType",
     *     headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *          {"name"="name", "dataType"="string", "required"=true, "description"="JerseyType name"},
     *          {"name"="blason", "dataType"="string", "required"=true, "description"="JerseyType blason"},
     *          {"name"="jerseyTypeStats", "dataType"="string", "required"=true, "description"="JerseyType stats"},
     *     }
     * )
     *
     */
    public function createAction(Request $request)
    {
        $json = $this->getJson($request)->toArray();

        $jerseyType = new JerseyType();
        $form    = $this->createForm(JerseyTypeType::class, $jerseyType);
        $form->submit($json);

        if (!$form->isValid()) {
            return new ApiResponse(null, 422, $this->getErrorMessages($form));
        } else {
            $em   = $this->getDoctrine()->getManager();
            $em->persist($jerseyType);
            $em->flush();
            return new ApiResponse(
                $jerseyType->serializeEntity()
            );
        }
    }

    /**
     * Get a jerseyType by id
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     * @ApiDoc(
     *     description="get jerseyType",
     *     section="JerseyType"
     * )
     *
     * @Route("/{id}", name="getJerseyType")
     * @Method("GET")
     *
     */
    public function getAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:JerseyType');
        $jerseyType = $repository->findOneById($request->get('id'));
        if (empty($jerseyType)) {
            return new ApiResponse(null, 404, ['JerseyType not found']);
        } else {
            return new ApiResponse(
                $jerseyType->serializeEntity()
            );
        }
    }


    /**
     * Edit a JerseyType
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc(
     *     description="Edit a jerseyType",
     *     section="JerseyType",
     *     headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *          {"name"="Name", "dataType"="string", "required"=true, "description"="JerseyType name"},
     *     }
     * )
     *
     * @Route("/{id}/edit", name="jerseyType_edit")
     * @Method("POST")
     *
     */
    public function editAction(Request $request)
    {
        $json       = $this->getJson($request)->toArray();
        $em         = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:JerseyType');
        $jerseyType = $repository->findOneById($request->get('id'));
        if (empty($jerseyType)) {
            return new ApiResponse(null, 404);
        }
        $editForm = $this->createForm(JerseyTypeType::class, $jerseyType);
        $editForm->submit($json);
        if ($editForm->isValid()) {
            $em->persist($jerseyType);
            $em->flush();
            return new ApiResponse(
                $jerseyType->serializeEntity()
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
     *     description="Delete existing JerseyType",
     *     section="JerseyType",
     *     headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     }
     * )
     *
     * @Route("/{id}/remove", name="jerseyTypeRemove")
     * @Method("DELETE")
     *
     */
    public function removeAction(Request $request)
    {
        try {
            $em         = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('AppBundle:JerseyType');
            $jerseyType = $repository->find($request->get('id'));
            if (!$jerseyType) {
                throw $this->createNotFoundException('Unable to find JerseyType id.');
            }
            $em->remove($jerseyType);
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
