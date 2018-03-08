<?php

namespace AppBundle\Controller;

use AppBundle\Entity\PlayerPosition;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Playerposition controller.
 *
 * @Route("playerposition")
 */
class PlayerPositionController extends Controller
{
    /**
     * Lists all playerPosition entities.
     *
     * @Route("/", name="playerposition_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $playerPositions = $em->getRepository('AppBundle:PlayerPosition')->findAll();

        return $this->render('playerposition/index.html.twig', array(
            'playerPositions' => $playerPositions,
        ));
    }

    /**
     * Creates a new playerPosition entity.
     *
     * @Route("/new", name="playerposition_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $playerPosition = new Playerposition();
        $form = $this->createForm('AppBundle\Form\PlayerPositionType', $playerPosition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($playerPosition);
            $em->flush();

            return $this->redirectToRoute('playerposition_show', array('id' => $playerPosition->getId()));
        }

        return $this->render('playerposition/new.html.twig', array(
            'playerPosition' => $playerPosition,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a playerPosition entity.
     *
     * @Route("/{id}", name="playerposition_show")
     * @Method("GET")
     */
    public function showAction(PlayerPosition $playerPosition)
    {
        $deleteForm = $this->createDeleteForm($playerPosition);

        return $this->render('playerposition/show.html.twig', array(
            'playerPosition' => $playerPosition,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing playerPosition entity.
     *
     * @Route("/{id}/edit", name="playerposition_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, PlayerPosition $playerPosition)
    {
        $deleteForm = $this->createDeleteForm($playerPosition);
        $editForm = $this->createForm('AppBundle\Form\PlayerPositionType', $playerPosition);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('playerposition_edit', array('id' => $playerPosition->getId()));
        }

        return $this->render('playerposition/edit.html.twig', array(
            'playerPosition' => $playerPosition,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a playerPosition entity.
     *
     * @Route("/{id}", name="playerposition_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, PlayerPosition $playerPosition)
    {
        $form = $this->createDeleteForm($playerPosition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($playerPosition);
            $em->flush();
        }

        return $this->redirectToRoute('playerposition_index');
    }

    /**
     * Creates a form to delete a playerPosition entity.
     *
     * @param PlayerPosition $playerPosition The playerPosition entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PlayerPosition $playerPosition)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('playerposition_delete', array('id' => $playerPosition->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
