<?php

namespace AppBundle\Controller;

use AppBundle\Entity\EventType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Eventtype controller.
 *
 * @Route("eventtype")
 */
class EventTypeController extends Controller
{
    /**
     * Lists all eventType entities.
     *
     * @Route("/", name="eventtype_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $eventTypes = $em->getRepository('AppBundle:EventType')->findAll();

        return $this->render('eventtype/index.html.twig', array(
            'eventTypes' => $eventTypes,
        ));
    }

    /**
     * Creates a new eventType entity.
     *
     * @Route("/new", name="eventtype_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $eventType = new Eventtype();
        $form = $this->createForm('AppBundle\Form\EventTypeType', $eventType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($eventType);
            $em->flush();

            return $this->redirectToRoute('eventtype_show', array('id' => $eventType->getId()));
        }

        return $this->render('eventtype/new.html.twig', array(
            'eventType' => $eventType,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a eventType entity.
     *
     * @Route("/{id}", name="eventtype_show")
     * @Method("GET")
     */
    public function showAction(EventType $eventType)
    {
        $deleteForm = $this->createDeleteForm($eventType);

        return $this->render('eventtype/show.html.twig', array(
            'eventType' => $eventType,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing eventType entity.
     *
     * @Route("/{id}/edit", name="eventtype_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, EventType $eventType)
    {
        $deleteForm = $this->createDeleteForm($eventType);
        $editForm = $this->createForm('AppBundle\Form\EventTypeType', $eventType);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('eventtype_edit', array('id' => $eventType->getId()));
        }

        return $this->render('eventtype/edit.html.twig', array(
            'eventType' => $eventType,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a eventType entity.
     *
     * @Route("/{id}", name="eventtype_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, EventType $eventType)
    {
        $form = $this->createDeleteForm($eventType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($eventType);
            $em->flush();
        }

        return $this->redirectToRoute('eventtype_index');
    }

    /**
     * Creates a form to delete a eventType entity.
     *
     * @param EventType $eventType The eventType entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(EventType $eventType)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('eventtype_delete', array('id' => $eventType->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
