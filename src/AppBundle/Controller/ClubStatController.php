<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ClubStat;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Clubstat controller.
 *
 * @Route("clubstat")
 */
class ClubStatController extends Controller
{
    /**
     * Lists all clubStat entities.
     *
     * @Route("/", name="clubstat_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $clubStats = $em->getRepository('AppBundle:ClubStat')->findAll();

        return $this->render('clubstat/index.html.twig', array(
            'clubStats' => $clubStats,
        ));
    }

    /**
     * Creates a new clubStat entity.
     *
     * @Route("/new", name="clubstat_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $clubStat = new Clubstat();
        $form = $this->createForm('AppBundle\Form\ClubStatType', $clubStat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($clubStat);
            $em->flush();

            return $this->redirectToRoute('clubstat_show', array('id' => $clubStat->getId()));
        }

        return $this->render('clubstat/new.html.twig', array(
            'clubStat' => $clubStat,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a clubStat entity.
     *
     * @Route("/{id}", name="clubstat_show")
     * @Method("GET")
     */
    public function showAction(ClubStat $clubStat)
    {
        $deleteForm = $this->createDeleteForm($clubStat);

        return $this->render('clubstat/show.html.twig', array(
            'clubStat' => $clubStat,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing clubStat entity.
     *
     * @Route("/{id}/edit", name="clubstat_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ClubStat $clubStat)
    {
        $deleteForm = $this->createDeleteForm($clubStat);
        $editForm = $this->createForm('AppBundle\Form\ClubStatType', $clubStat);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('clubstat_edit', array('id' => $clubStat->getId()));
        }

        return $this->render('clubstat/edit.html.twig', array(
            'clubStat' => $clubStat,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a clubStat entity.
     *
     * @Route("/{id}", name="clubstat_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ClubStat $clubStat)
    {
        $form = $this->createDeleteForm($clubStat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($clubStat);
            $em->flush();
        }

        return $this->redirectToRoute('clubstat_index');
    }

    /**
     * Creates a form to delete a clubStat entity.
     *
     * @param ClubStat $clubStat The clubStat entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ClubStat $clubStat)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('clubstat_delete', array('id' => $clubStat->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
