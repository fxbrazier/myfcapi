<?php

namespace AppBundle\Controller;

use AppBundle\Entity\PlayerStat;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Playerstat controller.
 *
 * @Route("playerstat")
 */
class PlayerStatController extends Controller
{
    /**
     * Lists all playerStat entities.
     *
     * @Route("/", name="playerstat_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $playerStats = $em->getRepository('AppBundle:PlayerStat')->findAll();

        return $this->render('playerstat/index.html.twig', array(
            'playerStats' => $playerStats,
        ));
    }

    /**
     * Creates a new playerStat entity.
     *
     * @Route("/new", name="playerstat_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $playerStat = new Playerstat();
        $form = $this->createForm('AppBundle\Form\PlayerStatType', $playerStat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($playerStat);
            $em->flush();

            return $this->redirectToRoute('playerstat_show', array('id' => $playerStat->getId()));
        }

        return $this->render('playerstat/new.html.twig', array(
            'playerStat' => $playerStat,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a playerStat entity.
     *
     * @Route("/{id}", name="playerstat_show")
     * @Method("GET")
     */
    public function showAction(PlayerStat $playerStat)
    {
        $deleteForm = $this->createDeleteForm($playerStat);

        return $this->render('playerstat/show.html.twig', array(
            'playerStat' => $playerStat,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing playerStat entity.
     *
     * @Route("/{id}/edit", name="playerstat_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, PlayerStat $playerStat)
    {
        $deleteForm = $this->createDeleteForm($playerStat);
        $editForm = $this->createForm('AppBundle\Form\PlayerStatType', $playerStat);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('playerstat_edit', array('id' => $playerStat->getId()));
        }

        return $this->render('playerstat/edit.html.twig', array(
            'playerStat' => $playerStat,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a playerStat entity.
     *
     * @Route("/{id}", name="playerstat_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, PlayerStat $playerStat)
    {
        $form = $this->createDeleteForm($playerStat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($playerStat);
            $em->flush();
        }

        return $this->redirectToRoute('playerstat_index');
    }

    /**
     * Creates a form to delete a playerStat entity.
     *
     * @param PlayerStat $playerStat The playerStat entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PlayerStat $playerStat)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('playerstat_delete', array('id' => $playerStat->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
