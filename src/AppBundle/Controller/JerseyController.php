<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Jersey;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Jersey controller.
 *
 * @Route("jersey")
 */
class JerseyController extends Controller
{
    /**
     * Lists all jersey entities.
     *
     * @Route("/", name="jersey_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $jerseys = $em->getRepository('AppBundle:Jersey')->findAll();

        return $this->render('jersey/index.html.twig', array(
            'jerseys' => $jerseys,
        ));
    }

    /**
     * Creates a new jersey entity.
     *
     * @Route("/new", name="jersey_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $jersey = new Jersey();
        $form = $this->createForm('AppBundle\Form\JerseyType', $jersey);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($jersey);
            $em->flush();

            return $this->redirectToRoute('jersey_show', array('id' => $jersey->getId()));
        }

        return $this->render('jersey/new.html.twig', array(
            'jersey' => $jersey,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a jersey entity.
     *
     * @Route("/{id}", name="jersey_show")
     * @Method("GET")
     */
    public function showAction(Jersey $jersey)
    {
        $deleteForm = $this->createDeleteForm($jersey);

        return $this->render('jersey/show.html.twig', array(
            'jersey' => $jersey,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing jersey entity.
     *
     * @Route("/{id}/edit", name="jersey_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Jersey $jersey)
    {
        $deleteForm = $this->createDeleteForm($jersey);
        $editForm = $this->createForm('AppBundle\Form\JerseyType', $jersey);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('jersey_edit', array('id' => $jersey->getId()));
        }

        return $this->render('jersey/edit.html.twig', array(
            'jersey' => $jersey,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a jersey entity.
     *
     * @Route("/{id}", name="jersey_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Jersey $jersey)
    {
        $form = $this->createDeleteForm($jersey);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($jersey);
            $em->flush();
        }

        return $this->redirectToRoute('jersey_index');
    }

    /**
     * Creates a form to delete a jersey entity.
     *
     * @param Jersey $jersey The jersey entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Jersey $jersey)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('jersey_delete', array('id' => $jersey->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
