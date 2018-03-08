<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Nationality;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Nationality controller.
 *
 * @Route("nationality")
 */
class NationalityController extends Controller
{
    /**
     * Lists all nationality entities.
     *
     * @Route("/", name="nationality_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $nationalities = $em->getRepository('AppBundle:Nationality')->findAll();

        return $this->render('nationality/index.html.twig', array(
            'nationalities' => $nationalities,
        ));
    }

    /**
     * Creates a new nationality entity.
     *
     * @Route("/new", name="nationality_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $nationality = new Nationality();
        $form = $this->createForm('AppBundle\Form\NationalityType', $nationality);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($nationality);
            $em->flush();

            return $this->redirectToRoute('nationality_show', array('id' => $nationality->getId()));
        }

        return $this->render('nationality/new.html.twig', array(
            'nationality' => $nationality,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a nationality entity.
     *
     * @Route("/{id}", name="nationality_show")
     * @Method("GET")
     */
    public function showAction(Nationality $nationality)
    {
        $deleteForm = $this->createDeleteForm($nationality);

        return $this->render('nationality/show.html.twig', array(
            'nationality' => $nationality,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing nationality entity.
     *
     * @Route("/{id}/edit", name="nationality_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Nationality $nationality)
    {
        $deleteForm = $this->createDeleteForm($nationality);
        $editForm = $this->createForm('AppBundle\Form\NationalityType', $nationality);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('nationality_edit', array('id' => $nationality->getId()));
        }

        return $this->render('nationality/edit.html.twig', array(
            'nationality' => $nationality,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a nationality entity.
     *
     * @Route("/{id}", name="nationality_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Nationality $nationality)
    {
        $form = $this->createDeleteForm($nationality);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($nationality);
            $em->flush();
        }

        return $this->redirectToRoute('nationality_index');
    }

    /**
     * Creates a form to delete a nationality entity.
     *
     * @param Nationality $nationality The nationality entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Nationality $nationality)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('nationality_delete', array('id' => $nationality->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
