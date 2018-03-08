<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Stadium;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Stadium controller.
 *
 * @Route("stadium")
 */
class StadiumController extends Controller
{
    /**
     * Lists all stadium entities.
     *
     * @Route("/", name="stadium_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $stadia = $em->getRepository('AppBundle:Stadium')->findAll();

        return $this->render('stadium/index.html.twig', array(
            'stadia' => $stadia,
        ));
    }

    /**
     * Creates a new stadium entity.
     *
     * @Route("/new", name="stadium_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $stadium = new Stadium();
        $form = $this->createForm('AppBundle\Form\StadiumType', $stadium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($stadium);
            $em->flush();

            return $this->redirectToRoute('stadium_show', array('id' => $stadium->getId()));
        }

        return $this->render('stadium/new.html.twig', array(
            'stadium' => $stadium,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a stadium entity.
     *
     * @Route("/{id}", name="stadium_show")
     * @Method("GET")
     */
    public function showAction(Stadium $stadium)
    {
        $deleteForm = $this->createDeleteForm($stadium);

        return $this->render('stadium/show.html.twig', array(
            'stadium' => $stadium,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing stadium entity.
     *
     * @Route("/{id}/edit", name="stadium_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Stadium $stadium)
    {
        $deleteForm = $this->createDeleteForm($stadium);
        $editForm = $this->createForm('AppBundle\Form\StadiumType', $stadium);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('stadium_edit', array('id' => $stadium->getId()));
        }

        return $this->render('stadium/edit.html.twig', array(
            'stadium' => $stadium,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a stadium entity.
     *
     * @Route("/{id}", name="stadium_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Stadium $stadium)
    {
        $form = $this->createDeleteForm($stadium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($stadium);
            $em->flush();
        }

        return $this->redirectToRoute('stadium_index');
    }

    /**
     * Creates a form to delete a stadium entity.
     *
     * @param Stadium $stadium The stadium entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Stadium $stadium)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('stadium_delete', array('id' => $stadium->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
