<?php

namespace AppBundle\Controller;

use AppBundle\Entity\JerseyType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Jerseytype controller.
 *
 * @Route("jerseytype")
 */
class JerseyTypeController extends Controller
{
    /**
     * Lists all jerseyType entities.
     *
     * @Route("/", name="jerseytype_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $jerseyTypes = $em->getRepository('AppBundle:JerseyType')->findAll();

        return $this->render('jerseytype/index.html.twig', array(
            'jerseyTypes' => $jerseyTypes,
        ));
    }

    /**
     * Creates a new jerseyType entity.
     *
     * @Route("/new", name="jerseytype_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $jerseyType = new Jerseytype();
        $form = $this->createForm('AppBundle\Form\JerseyTypeType', $jerseyType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($jerseyType);
            $em->flush();

            return $this->redirectToRoute('jerseytype_show', array('id' => $jerseyType->getId()));
        }

        return $this->render('jerseytype/new.html.twig', array(
            'jerseyType' => $jerseyType,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a jerseyType entity.
     *
     * @Route("/{id}", name="jerseytype_show")
     * @Method("GET")
     */
    public function showAction(JerseyType $jerseyType)
    {
        $deleteForm = $this->createDeleteForm($jerseyType);

        return $this->render('jerseytype/show.html.twig', array(
            'jerseyType' => $jerseyType,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing jerseyType entity.
     *
     * @Route("/{id}/edit", name="jerseytype_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, JerseyType $jerseyType)
    {
        $deleteForm = $this->createDeleteForm($jerseyType);
        $editForm = $this->createForm('AppBundle\Form\JerseyTypeType', $jerseyType);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('jerseytype_edit', array('id' => $jerseyType->getId()));
        }

        return $this->render('jerseytype/edit.html.twig', array(
            'jerseyType' => $jerseyType,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a jerseyType entity.
     *
     * @Route("/{id}", name="jerseytype_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, JerseyType $jerseyType)
    {
        $form = $this->createDeleteForm($jerseyType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($jerseyType);
            $em->flush();
        }

        return $this->redirectToRoute('jerseytype_index');
    }

    /**
     * Creates a form to delete a jerseyType entity.
     *
     * @param JerseyType $jerseyType The jerseyType entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(JerseyType $jerseyType)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('jerseytype_delete', array('id' => $jerseyType->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
