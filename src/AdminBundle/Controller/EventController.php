<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Event;
use AdminBundle\Form\EventType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AdminBundle\Helper\Response\ApiResponse;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Exception;


/**
 * Event controller.
 *
 * @Route("api/event")
 */
class EventController extends JsonController
{
    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @Route("s", name="eventList")
     * @Method("POST")
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc (
     *
     *  description="Events with orders, pagination and research",
     *  section="Event",
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
                    'AppBundle\Entity\Event',
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
     * @Route("/new", name="eventCreate")
     * @Method("POST")
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc(
     *     description="Creates a new event",
     *     section="Event",
     *     headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *          {"name"="label", "dataType"="string", "required"=true, "description"="Event label"},
     *          {"name"="date", "dataType"="dateTime", "required"=true, "description"="Event date"},
     *     }
     * )
     *
     */
    public function createAction(Request $request)
    {
        $json = $this->getJson($request)->toArray();

        $event = new Event();
        $form    = $this->createForm(EventType::class, $event);
        $form->submit($json);

        if (!$form->isValid()) {
            return new ApiResponse(null, 422, $this->getErrorMessages($form));
        } else {
            $em   = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
            return new ApiResponse(
                $event->serializeEntity()
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
     *     section="Event"
     * )
     *
     * @Route("/{id}", name="getEvent")
     * @Method("GET")
     *
     */
    public function getAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Event');
        $event = $repository->findOneById($request->get('id'));
        if (empty($event)) {
            return new ApiResponse(null, 404, ['Event not found']);
        } else {
            return new ApiResponse(
                $event->serializeEntity()
            );
        }
    }


    /**
     * Edit a Event
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc(
     *     description="Edit a event",
     *     section="Event",
     *     headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *          {"name"="label", "dataType"="string", "required"=true, "description"="Event label"},
     *     }
     * )
     *
     * @Route("/{id}/edit", name="eventEdit")
     * @Method("POST")
     *
     */
    public function editAction(Request $request)
    {
        $json       = $this->getJson($request)->toArray();
        $em         = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Event');
        $event = $repository->findOneById($request->get('id'));
        if (empty($event)) {
            return new ApiResponse(null, 404);
        }
        $editForm = $this->createForm(EventFormType::class, $event);
        $editForm->submit($json);
        if ($editForm->isValid()) {
            $em->persist($event);
            $em->flush();
            return new ApiResponse(
                $event->serializeEntity()
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
     *     description="Delete existing Event",
     *     section="Event",
     *     headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     }
     * )
     *
     * @Route("/{id}/remove", name="eventRemove")
     * @Method("DELETE")
     *
     */
    public function removeAction(Request $request)
    {
        try {
            $em         = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('AppBundle:Event');
            $event = $repository->find($request->get('id'));
            if (!$event) {
                throw $this->createNotFoundException('Unable to find Event id.');
            }
            $em->remove($event);
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
