<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Contrat;
use AdminBundle\Form\ContractType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AdminBundle\Helper\Response\ApiResponse;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Exception;


/**
 * Contrat controller.
 *
 * @Route("api/contract")
 */
class ContratController extends JsonController
{
    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @Route("s", name="contractList")
     * @Method("POST")
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc (
     *
     *  description="Contracts with orders, pagination and research",
     *  section="Contract",
     *
     *  parameters={
     *      {"name"="orders", "dataType"="array", "required"=false, "format"="[ ['id', 'desc'] ]"},
     *      {"name"="page", "dataType"="integer", "required"=false, "description"="Page number (1 by default)"},
     *      {"name"="perPage", "dataType"="integer", "required"=false, "description"="Items per page"},
     *      {"name"="search", "dataType"="string", "required"=false, "description"="Search on multiple columns"}
     *  }
     * )
     */
    public function listAction(Request $request)
    {
        return new ApiResponse(
            $this->get('fc5.entities_list_handler')
                ->handleList(
                    'AppBundle\Entity\Contrat',
                    [
                        'id',
                    ]
                )
                ->getResults()
        );
    }


    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @Route("/new", name="contractCreate")
     * @Method("POST")
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc(
     *     description="Creates a new contract",
     *     section="Contract",
     *     headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *          {"name"="salaire", "dataType"="integer", "required"=true, "description"="Player salary"},
     *          {"name"="description", "dataType"="string", "required"=true, "description"="Contract description"},
     *          {"name"="value", "dataType"="integer", "required"=true, "description"="Contract value"},
     *          {"name"="dateStart", "dataType"="dateTime", "required"=true, "description"="Contract Date Start"},
     *          {"name"="duration", "dataType"="dateTime", "required"=true, "description"="Contract Duration"},
     *          {"name"="onGoing", "dataType"="boolean", "required"=true, "description"="Is the contract ongoing"},
     *     }
     * )
     *
     */
    public function createAction(Request $request)
    {
        $json = $this->getJson($request)->toArray();

        $contract = new Contrat();
        $form    = $this->createForm(ContratType::class, $contract);
        $form->submit($json);

        if (!$form->isValid()) {
            return new ApiResponse(null, 422, $this->getErrorMessages($form));
        } else {
            $em   = $this->getDoctrine()->getManager();
            $em->persist($contract);
            $em->flush();
            return new ApiResponse(
                $contract->serializeEntity()
            );
        }
    }

    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     * @ApiDoc(
     *     description="get a contract",
     *     section="Contract"
     * )
     *
     * @Route("/{id}", name="getContract")
     * @Method("GET")
     *
     */
    public function getAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:ClubStat');
        $contract = $repository->findOneById($request->get('id'));
        if (empty($contract)) {
            return new ApiResponse(null, 404, ['Club not found']);
        } else {
            return new ApiResponse(
                $contract->serializeEntity()
            );
        }
    }


    /**
     * Edit a Contract
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc(
     *     description="Edit a contract",
     *     section="Contract",
     *     headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     },
     *     parameters={
     *          {"name"="id", "dataType"="string", "required"=true, "description"="Contract id"},
     *     }
     * )
     *
     * @Route("/{id}/edit", name="contractEdit")
     * @Method("POST")
     *
     */
    public function editAction(Request $request)
    {
        $json       = $this->getJson($request)->toArray();
        $em         = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Contrat');
        $contract = $repository->findOneById($request->get('id'));
        if (empty($contract)) {
            return new ApiResponse(null, 404);
        }
        $editForm = $this->createForm(ClubFormType::class, $contract);
        $editForm->submit($json);
        if ($editForm->isValid()) {
            $em->persist($contract);
            $em->flush();
            return new ApiResponse(
                $contract->serializeEntity()
            );
        } else {
            return new ApiResponse(null, 422, $this->getErrorMessages($editForm));
        }
    }


    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \AdminBundle\Helper\Response\ApiResponse
     *
     * @ApiDoc(
     *     description="Delete existing Contract",
     *     section="Contract",
     *     headers={
     *          {
     *              "name"="X-Auth-Token",
     *              "description"="Auth Token",
     *              "required"=true
     *          }
     *     }
     * )
     *
     * @Route("/{id}/remove", name="contractRemove")
     * @Method("DELETE")
     *
     */
    public function removeAction(Request $request)
    {
        try {
            $em         = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('AppBundle:Contrat');
            $contract = $repository->find($request->get('id'));
            if (!$contract) {
                throw $this->createNotFoundException('Unable to find Contract id.');
            }
            $em->remove($contract);
            $em->flush();
            return new ApiResponse(
                [
                    'success' => true,
                ]
            );
        } catch (Exception $e) {
            return new ApiResponse(null, 404, $e->getMessage());
        }
    }
}
