<?php

namespace AppBundle\Controller;

use AppBundle\Entity\LeagueResult;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Leagueresult controller.
 *
 * @Route("leagueresult")
 */
class LeagueResultController extends Controller
{
    /**
     * Lists all leagueResult entities.
     *
     * @Route("/", name="leagueresult_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $leagueResults = $em->getRepository('AppBundle:LeagueResult')->findAll();

        return $this->render('leagueresult/index.html.twig', array(
            'leagueResults' => $leagueResults,
        ));
    }

    /**
     * Creates a new leagueResult entity.
     *
     * @Route("/new", name="leagueresult_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $leagueResult = new Leagueresult();
        $form = $this->createForm('AppBundle\Form\LeagueResultType', $leagueResult);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($leagueResult);
            $em->flush();

            return $this->redirectToRoute('leagueresult_show', array('id' => $leagueResult->getId()));
        }

        return $this->render('leagueresult/new.html.twig', array(
            'leagueResult' => $leagueResult,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a leagueResult entity.
     *
     * @Route("/{id}", name="leagueresult_show")
     * @Method("GET")
     */
    public function showAction(LeagueResult $leagueResult)
    {
        $deleteForm = $this->createDeleteForm($leagueResult);

        return $this->render('leagueresult/show.html.twig', array(
            'leagueResult' => $leagueResult,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing leagueResult entity.
     *
     * @Route("/{id}/edit", name="leagueresult_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, LeagueResult $leagueResult)
    {
        $deleteForm = $this->createDeleteForm($leagueResult);
        $editForm = $this->createForm('AppBundle\Form\LeagueResultType', $leagueResult);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('leagueresult_edit', array('id' => $leagueResult->getId()));
        }

        return $this->render('leagueresult/edit.html.twig', array(
            'leagueResult' => $leagueResult,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a leagueResult entity.
     *
     * @Route("/{id}", name="leagueresult_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, LeagueResult $leagueResult)
    {
        $form = $this->createDeleteForm($leagueResult);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($leagueResult);
            $em->flush();
        }

        return $this->redirectToRoute('leagueresult_index');
    }

    /**
     * Creates a form to delete a leagueResult entity.
     *
     * @param LeagueResult $leagueResult The leagueResult entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(LeagueResult $leagueResult)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('leagueresult_delete', array('id' => $leagueResult->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
