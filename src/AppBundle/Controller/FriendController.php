<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Friend;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Friend controller.
 *
 * @Route("friend")
 */
class FriendController extends Controller
{
    /**
     * Lists all friend entities.
     *
     * @Route("/", name="friend_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $friends = $em->getRepository('AppBundle:Friend')->findAll();

        return $this->render('friend/index.html.twig', array(
            'friends' => $friends,
        ));
    }

    /**
     * Creates a new friend entity.
     *
     * @Route("/new", name="friend_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $friend = new Friend();
        $form = $this->createForm('AppBundle\Form\FriendType', $friend);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($friend);
            $em->flush();

            return $this->redirectToRoute('friend_show', array('id' => $friend->getId()));
        }

        return $this->render('friend/new.html.twig', array(
            'friend' => $friend,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a friend entity.
     *
     * @Route("/{id}", name="friend_show")
     * @Method("GET")
     */
    public function showAction(Friend $friend)
    {
        $deleteForm = $this->createDeleteForm($friend);

        return $this->render('friend/show.html.twig', array(
            'friend' => $friend,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing friend entity.
     *
     * @Route("/{id}/edit", name="friend_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Friend $friend)
    {
        $deleteForm = $this->createDeleteForm($friend);
        $editForm = $this->createForm('AppBundle\Form\FriendType', $friend);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('friend_edit', array('id' => $friend->getId()));
        }

        return $this->render('friend/edit.html.twig', array(
            'friend' => $friend,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a friend entity.
     *
     * @Route("/{id}", name="friend_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Friend $friend)
    {
        $form = $this->createDeleteForm($friend);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($friend);
            $em->flush();
        }

        return $this->redirectToRoute('friend_index');
    }

    /**
     * Creates a form to delete a friend entity.
     *
     * @param Friend $friend The friend entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Friend $friend)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('friend_delete', array('id' => $friend->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
