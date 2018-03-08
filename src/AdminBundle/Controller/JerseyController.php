<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Jersey;
use AdminBundle\Form\JerseyType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use AdminBundle\Helper\Response\ApiResponse;
use Exception;


/**
 * Jersey controller.
 *
 * @Route("api/jersey")
 */
class JerseyController extends JsonController
{
    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @Route("s", name="jerseyList")
     * @Method("POST")
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc (
     *
     *  description="Jerseys with orders, pagination and research",
     *  section="Jersey",
     *
     *  parameters={
     *      {"name"="orders", "dataType"="array", "required"=false, "format"="[ ['id', 'asc'] ]"},
     *      {"name"="page", "dataType"="integer", "required"=false, "description"="Page number (1 by default)"},
     *      {"name"="perPage", "dataType"="integer", "required"=true, "description"="Items per page (-1 default)"},
     *      {"name"="search", "dataType"="string", "required"=false, "description"="Search on multiple columns"}
     *  }
     * )
     */
    public function listAction(Request $request)
    {
        return new ApiResponse(
            $this->get('fc5.entities_list_handler')
                ->handleList(
                    'AppBundle\Entity\Jersey',
                    [
                        'id',
                        'jerseyType.label',
                    ]
                )
                ->getResults()
        );
    }


    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @Route("/new", name="jersey_create")
     * @Method("POST")
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc(
     *     description="Creates a new jersey",
     *     section="Jersey",
     *     headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *          {"name"="name", "dataType"="string", "required"=true, "description"="Jersey name"},
     *          {"name"="blason", "dataType"="string", "required"=true, "description"="Jersey blason"},
     *          {"name"="jerseyStats", "dataType"="string", "required"=true, "description"="Jersey stats"},
     *     }
     * )
     *
     */
    public function createAction(Request $request)
    {
        $json = $this->getJson($request)->toArray();

        $jersey = new Jersey();
        $form    = $this->createForm(JerseyType::class, $jersey);
        $form->submit($json);

        if (!$form->isValid()) {
            return new ApiResponse(null, 422, $this->getErrorMessages($form));
        } else {
            $em   = $this->getDoctrine()->getManager();
            $em->persist($jersey);
            $em->flush();
            return new ApiResponse(
                $jersey->serializeEntity()
            );
        }
    }

    /**
     * Get a jersey by id
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     * @ApiDoc(
     *     description="get jersey",
     *     section="Jersey"
     * )
     *
     * @Route("/{id}", name="getJersey")
     * @Method("GET")
     *
     */
    public function getAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Jersey');
        $jersey = $repository->findOneById($request->get('id'));
        if (empty($jersey)) {
            return new ApiResponse(null, 404, ['Jersey not found']);
        } else {
            return new ApiResponse(
                $jersey->serializeEntity()
            );
        }
    }


    /**
     * Edit a Jersey
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc(
     *     description="Edit a jersey",
     *     section="Jersey",
     *     headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *          {"name"="Name", "dataType"="string", "required"=true, "description"="Jersey name"},
     *     }
     * )
     *
     * @Route("/{id}/edit", name="jersey_edit")
     * @Method("POST")
     *
     */
    public function editAction(Request $request)
    {
        $json       = $this->getJson($request)->toArray();
        $em         = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Jersey');
        $jersey = $repository->findOneById($request->get('id'));
        if (empty($jersey)) {
            return new ApiResponse(null, 404);
        }
        $editForm = $this->createForm(JerseyType::class, $jersey);
        $editForm->submit($json);
        if ($editForm->isValid()) {
            $em->persist($jersey);
            $em->flush();
            return new ApiResponse(
                $jersey->serializeEntity()
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
     *     description="Delete existing Jersey",
     *     section="Jersey",
     *     headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     }
     * )
     *
     * @Route("/{id}/remove", name="jerseyRemove")
     * @Method("DELETE")
     *
     */
    public function removeAction(Request $request)
    {
        try {
            $em         = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('AppBundle:Jersey');
            $jersey = $repository->find($request->get('id'));
            if (!$jersey) {
                throw $this->createNotFoundException('Unable to find Jersey id.');
            }
            $em->remove($jersey);
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
