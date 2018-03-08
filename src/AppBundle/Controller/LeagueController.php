<?php

namespace AppBundle\Controller;

use AppBundle\Entity\League;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * League controller.
 *
 * @Route("league")
 */
class LeagueController extends Controller
{
    /**
     * Lists all league entities.
     *
     * @Route("/", name="league_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $leagues = $em->getRepository('AppBundle:League')->findAll();

        return $this->render('league/index.html.twig', array(
            'leagues' => $leagues,
        ));
    }

    /**
     * Creates a new league entity.
     *
     * @Route("/new", name="league_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $league = new League();
        $form = $this->createForm('AppBundle\Form\LeagueType', $league);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($league);
            $em->flush();

            return $this->redirectToRoute('league_show', array('id' => $league->getId()));
        }

        return $this->render('league/new.html.twig', array(
            'league' => $league,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a league entity.
     *
     * @Route("/{id}", name="league_show")
     * @Method("GET")
     */
    public function showAction(League $league)
    {
        $deleteForm = $this->createDeleteForm($league);

        return $this->render('league/show.html.twig', array(
            'league' => $league,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing league entity.
     *
     * @Route("/{id}/edit", name="league_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, League $league)
    {
        $deleteForm = $this->createDeleteForm($league);
        $editForm = $this->createForm('AppBundle\Form\LeagueType', $league);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('league_edit', array('id' => $league->getId()));
        }

        return $this->render('league/edit.html.twig', array(
            'league' => $league,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a league entity.
     *
     * @Route("/{id}", name="league_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, League $league)
    {
        $form = $this->createDeleteForm($league);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($league);
            $em->flush();
        }

        return $this->redirectToRoute('league_index');
    }

    /**
     * Creates a form to delete a league entity.
     *
     * @param League $league The league entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(League $league)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('league_delete', array('id' => $league->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
