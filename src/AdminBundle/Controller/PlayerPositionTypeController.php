<?php

namespace AppBundle\Controller;

use AppBundle\Entity\PlayerPositionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Playerpositiontype controller.
 *
 * @Route("playerpositiontype")
 */
class PlayerPositionTypeController extends Controller
{
    /**
     * Lists all playerPositionType entities.
     *
     * @Route("/", name="playerpositiontype_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $playerPositionTypes = $em->getRepository('AppBundle:PlayerPositionType')->findAll();

        return $this->render('playerpositiontype/index.html.twig', array(
            'playerPositionTypes' => $playerPositionTypes,
        ));
    }

    /**
     * Creates a new playerPositionType entity.
     *
     * @Route("/new", name="playerpositiontype_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $playerPositionType = new Playerpositiontype();
        $form = $this->createForm('AppBundle\Form\PlayerPositionTypeType', $playerPositionType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($playerPositionType);
            $em->flush();

            return $this->redirectToRoute('playerpositiontype_show', array('id' => $playerPositionType->getId()));
        }

        return $this->render('playerpositiontype/new.html.twig', array(
            'playerPositionType' => $playerPositionType,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a playerPositionType entity.
     *
     * @Route("/{id}", name="playerpositiontype_show")
     * @Method("GET")
     */
    public function showAction(PlayerPositionType $playerPositionType)
    {
        $deleteForm = $this->createDeleteForm($playerPositionType);

        return $this->render('playerpositiontype/show.html.twig', array(
            'playerPositionType' => $playerPositionType,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing playerPositionType entity.
     *
     * @Route("/{id}/edit", name="playerpositiontype_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, PlayerPositionType $playerPositionType)
    {
        $deleteForm = $this->createDeleteForm($playerPositionType);
        $editForm = $this->createForm('AppBundle\Form\PlayerPositionTypeType', $playerPositionType);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('playerpositiontype_edit', array('id' => $playerPositionType->getId()));
        }

        return $this->render('playerpositiontype/edit.html.twig', array(
            'playerPositionType' => $playerPositionType,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a playerPositionType entity.
     *
     * @Route("/{id}", name="playerpositiontype_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, PlayerPositionType $playerPositionType)
    {
        $form = $this->createDeleteForm($playerPositionType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($playerPositionType);
            $em->flush();
        }

        return $this->redirectToRoute('playerpositiontype_index');
    }

    /**
     * Creates a form to delete a playerPositionType entity.
     *
     * @param PlayerPositionType $playerPositionType The playerPositionType entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PlayerPositionType $playerPositionType)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('playerpositiontype_delete', array('id' => $playerPositionType->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
