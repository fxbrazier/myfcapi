<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\EventType;
use AdminBundle\Form\EventTypeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use AdminBundle\Helper\Response\ApiResponse;
use Exception;

/**
 * Eventtype controller.
 *
 * @Route("api/eventtype")
 */
class EventTypeController extends JsonController
{
    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @Route("s", name="eventTypeList")
     * @Method("POST")
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc (
     *
     *  description="EventTypes with orders, pagination and research",
     *  section="EventType",
     *
     *  parameters={
     *      {"name"="orders", "dataType"="array", "required"=false, "format"="[ ['label', 'desc'] ]"},
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
                    'AppBundle\Entity\EventType',
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
     * @Route("/new", name="eventTypeCreate")
     * @Method("POST")
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc(
     *     description="Creates a new event",
     *     section="EventType",
     *     headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *          {"name"="label", "dataType"="string", "required"=true, "description"="EventType label"},
     *     }
     * )
     *
     */
    public function createAction(Request $request)
    {
        $json = $this->getJson($request)->toArray();

        $eventType = new EventType();
        $form    = $this->createForm(EventTypeType::class, $eventType);
        $form->submit($json);

        if (!$form->isValid()) {
            return new ApiResponse(null, 422, $this->getErrorMessages($form));
        } else {
            $em   = $this->getDoctrine()->getManager();
            $em->persist($eventType);
            $em->flush();
            return new ApiResponse(
                $eventType->serializeEntity()
            );
        }
    }

    /**
     * Get a event by id
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     * @ApiDoc(
     *     description="get event",
     *     section="EventType"
     * )
     *
     * @Route("/{id}", name="getEventType")
     * @Method("GET")
     *
     */
    public function getAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:EventType');
        $eventType = $repository->findOneById($request->get('id'));
        if (empty($eventType)) {
            return new ApiResponse(null, 404, ['EventType not found']);
        } else {
            return new ApiResponse(
                $eventType->serializeEntity()
            );
        }
    }


    /**
     * Edit a EventType
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc(
     *     description="Edit a event",
     *     section="EventType",
     *     headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *          {"name"="Name", "dataType"="string", "required"=true, "description"="EventType name"},
     *     }
     * )
     *
     * @Route("/{id}/edit", name="eventTypeEdit")
     * @Method("POST")
     *
     */
    public function editAction(Request $request)
    {
        $json       = $this->getJson($request)->toArray();
        $em         = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:EventType');
        $eventType = $repository->findOneById($request->get('id'));
        if (empty($eventType)) {
            return new ApiResponse(null, 404);
        }
        $editForm = $this->createForm(EventTypeType::class, $eventType);
        $editForm->submit($json);
        if ($editForm->isValid()) {
            $em->persist($eventType);
            $em->flush();
            return new ApiResponse(
                $eventType->serializeEntity()
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
     *     description="Delete existing EventType",
     *     section="EventType",
     *     headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     }
     * )
     *
     * @Route("/{id}/remove", name="eventTypeRemove")
     * @Method("DELETE")
     *
     */
    public function removeAction(Request $request)
    {
        try {
            $em         = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('AppBundle:EventType');
            $eventType = $repository->find($request->get('id'));
            if (!$eventType) {
                throw $this->createNotFoundException('Unable to find EventType id.');
            }
            $em->remove($eventType);
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
