<?php

namespace App\Controller;

use App\Entity\LiquidPartsDetails;
use App\Form\EntryReportType;
use App\Form\LiquideIndexType;
use App\Form\LiquidPartsDetailsType;
use App\Form\LiquidWastageModifyType;
use App\Form\SetWastageQuantityType;
use App\Form\SetWastageSoldType;
use App\Repository\LiquidPartsDetailsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/liquid/parts/details")
 * @IsGranted("ROLE_ADMIN")
 */
class LiquidPartsDetailsController extends AbstractController
{
    /**
     * @Route("/", name="liquid_parts_details_index")
     * @param LiquidPartsDetailsRepository $liquidPartsDetailsRepository
     * @param Request $request
     * @return Response
     */
    public function index(LiquidPartsDetailsRepository $liquidPartsDetailsRepository, Request $request): Response
    {
        $spareParts = '';
        $statusType = '';
       // $liquid_parts_details = '';

        $findFromDate = date("m/d/y");
        $findToDate = date("m/d/y");
        $form = $this->createForm(LiquideIndexType::class);

        $formData = $request->request->all();
        if ($request->isMethod('POST')) {
            $findFromDate = ($formData['liquide_index']['findFromDate']!="")?$formData['liquide_index']['findFromDate']:date("m/d/y");
            $findToDate = ($formData['liquide_index']['findToDate']!="")?$formData['liquide_index']['findToDate']:date("m/d/y");
            $spareParts = ($formData['liquide_index']['spareParts']!="")?$formData['liquide_index']['spareParts']:'';
            $statusType = ($formData['liquide_index']['statusType']!="")?$formData['liquide_index']['statusType']:'';
        }
        $liquid_parts_details = $liquidPartsDetailsRepository->getLiquidSpearsPartsListByCondition($findFromDate, $findToDate, $spareParts, $statusType);

        return $this->render('liquid_parts_details/index.html.twig', [
            'liquid_parts_details' => $liquid_parts_details,
            'form' => $form->createView(),
            'findFromDate' => $findFromDate,
            'findToDate' => $findToDate,
            'spareParts' => $spareParts,
            'statusType' => $statusType,
        ]);
    }

    /**
     * @Route("/new", name="liquid_parts_details_new", methods={"GET","POST"})
     * @param LiquidPartsDetailsRepository $liquidPartsDetailsRepository
     * @param Request $request
     * @return Response
     */
    public function new(LiquidPartsDetailsRepository $liquidPartsDetailsRepository, Request $request): Response
    {
        $spareParts = '';
        $statusType = '';
        $car = '';
        $action = false;
        $form1 = '';
        $availableQuantity= 0;
        $form = $this->createForm(LiquidPartsDetailsType::class);
        $formData = $request->request->all();
        if ($request->isMethod('POST')) {
            if( $formData['liquid_parts_details']['spareParts']==""){
                $this->addFlash(
                    'notice',
                    'Please Select Spears Parts'
                );
                return $this->redirectToRoute('liquid_parts_details_new');
            }
            $action = true;
            $spareParts = ($formData['liquid_parts_details']['spareParts']!="")?$formData['liquid_parts_details']['spareParts']:'';
            $statusType = ($formData['liquid_parts_details']['statusType']!="")?$formData['liquid_parts_details']['statusType']:'1';
//            $car        = ($formData['liquid_parts_details']['car']!="")?$formData['liquid_parts_details']['car']:'';
            $availableQuantity = $liquidPartsDetailsRepository->getAvailAbleWastage($spareParts)['available_quantity'];

            if($statusType=='1'){
                $formBuild = $this->createForm(SetWastageQuantityType::class,[],array(
                    'action' => $this->generateUrl('liquid_wastage_modify'),
                ));
                $form1 = $formBuild->createView();
            }else{
                $formBuild = $this->createForm(SetWastageSoldType::class,[],array(
                    'action' => $this->generateUrl('liquid_wastage_sold'),
                ));
                $form1 = $formBuild->createView();
            }


        }

        return $this->render('liquid_parts_details/new.html.twig', [

            'form' => $form->createView(),
            'form1'=>  $form1,
            'spareParts'    => $spareParts,
            'statusType'    => $statusType,
            'car'    => $car,
            'availableQuantity' => $availableQuantity,
            'action' => $action
        ]);
    }


    /**
     * @Route("/liquid/wastage", name="liquid_wastage_modify", methods={"GET","POST"})
     * @param LiquidPartsDetailsRepository $liquidPartsDetailsRepository
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateLiquidWastage( LiquidPartsDetailsRepository $liquidPartsDetailsRepository,  Request $request)
    {
        $entity = $this->getDoctrine()->getManager();
        $data = $request->request->all();
        if ($request->isMethod('POST') && $data['set_wastage_quantity']['wastageQuantity']!="" && $data['set_wastage_quantity']['wastageQuantity']>0 && $data['set_wastage_quantity']['spearsParts']!="") {
            $wastageQuantity = $data['set_wastage_quantity']['wastageQuantity'];
            //echo $wastageQuantity;die();
            $spearsPartsID = $data['set_wastage_quantity']['spearsParts'];
            $spearsParts = $this->getDoctrine()->getRepository('App:SpareParts')->find($spearsPartsID);
            $isEditedID = $data['set_wastage_quantity']['isEditedID'];
            if($isEditedID!=""){
                $liquidPartsDetails = $liquidPartsDetailsRepository->find($isEditedID);
                $availableQuantity = $liquidPartsDetailsRepository->getAvailAbleWastage($spearsPartsID)['available_quantity'];
                if($wastageQuantity>$availableQuantity){
                    $this->addFlash(
                        'notice',
                        'Quantity Exceed over the available quantity'
                    );
                    return $this->redirectToRoute('liquid_parts_details_index');
                }
                $liquidPartsDetails->setUpdateAt(new \DateTime());
            }else{
                $liquidPartsDetails = new LiquidPartsDetails();
                $liquidPartsDetails->setCreateAt(new \DateTime());
            }

            $liquidPartsDetails->setWastageQuantity($wastageQuantity);

            $liquidPartsDetails->setSpearsParts($spearsParts);
            $entity->persist($liquidPartsDetails);
            $entity->flush();
         $this->addFlash(
                'success',
                'Make Wastage Successfully Completed'
            );
            return $this->redirectToRoute('liquid_parts_details_index');
        }else{
            $this->addFlash(
                'notice',
                'Please Enter Valid Data'
            );
            return $this->redirectToRoute('liquid_parts_details_index');
        }

    }



    /**
     * @Route("/liquid/sold", name="liquid_wastage_sold", methods={"GET","POST"})
     * @param LiquidPartsDetailsRepository $liquidPartsDetailsRepository
     * @param Request $request
     * @return RedirectResponse
     */
    public function soldLiquidWastage( LiquidPartsDetailsRepository $liquidPartsDetailsRepository,  Request $request)
    {
        $entity = $this->getDoctrine()->getManager();
        $data = $request->request->all();
        if ($request->isMethod('POST') && $data['set_wastage_sold']['soldQuantity']!="" && $data['set_wastage_sold']['soldQuantity']>0 && $data['set_wastage_sold']['spearsParts']!="" && $data['set_wastage_sold']['soldPrice']!="" && $data['set_wastage_sold']['soldPrice']>0){
            $wastageQuantity = $data['set_wastage_sold']['soldQuantity'];
            //echo $wastageQuantity;die();
            $spearsPartsID = $data['set_wastage_sold']['spearsParts'];
            $spearsParts = $this->getDoctrine()->getRepository('App:SpareParts')->find($spearsPartsID);
            $isEditedID = $data['set_wastage_sold']['isEditedID'];
            if($isEditedID!=""){
                $liquidPartsDetails = $liquidPartsDetailsRepository->find($isEditedID);
                $availableQuantity = $liquidPartsDetailsRepository->getAvailAbleWastage($spearsPartsID)['available_quantity'];
                if($wastageQuantity>$availableQuantity){
                    $this->addFlash(
                        'notice',
                        'Quantity Exceed over the available quantity'
                    );
                    return $this->redirectToRoute('liquid_parts_details_index');
                }
                $liquidPartsDetails->setUpdateAt(new \DateTime());
            }else{
                $liquidPartsDetails = new LiquidPartsDetails();
                $liquidPartsDetails->setCreateAt(new \DateTime());
            }
            $liquidPartsDetails->setSoldQuantity($wastageQuantity);
            $liquidPartsDetails->setSoldPrice($data['set_wastage_sold']['soldPrice']);

            $liquidPartsDetails->setSpearsParts($spearsParts);
            $entity->persist($liquidPartsDetails);
            $entity->flush();
            $this->addFlash(
                'success',
                'Make Wastage Successfully Completed'
            );
            return $this->redirectToRoute('liquid_parts_details_index');
        }else{
            $this->addFlash(
                'notice',
                'Please Enter Valid Data'
            );
            return $this->redirectToRoute('liquid_parts_details_index');
        }

    }

    /**
     * @Route("/liquid/sold/{id}", name="modify_liquid_item")
     * @param LiquidPartsDetailsRepository $liquidPartsDetailsRepository
     * @param Request $request
     * @return JsonResponse
     */
    public function showForm( LiquidPartsDetailsRepository $liquidPartsDetailsRepository, Request $request): JsonResponse
    {
        if ($request->isXmlHttpRequest()) {
            $liquidItemWastageId = $request->request->get('id');


            $liquidPartsDetails = $liquidPartsDetailsRepository->find($liquidItemWastageId);

            if($liquidPartsDetails==""){
                return new JsonResponse(
                    array(
                        'success' => false,
                        'formHtml' => 'Please Try Again !'
                    )
                );
            }
            $spareParts = $liquidPartsDetails->getSpearsParts()->getId();

            $checkWastageStatus = $liquidPartsDetails->getWastageQuantity();
            $availableQuantity = $liquidPartsDetailsRepository->getAvailAbleWastage($spareParts)['available_quantity'];
            if($checkWastageStatus>0){
                $form = $this->createForm(SetWastageQuantityType::class,[],array(
                    'action' => $this->generateUrl('liquid_wastage_modify'),
                ));
                $formHtml = $this->renderView('liquid_parts_details/modify.html.twig', array(
                    'form' => $form->createView(),
                    'liquidPartsDetails'=> $liquidPartsDetails,
                    'liquidItemWastageId'=>$liquidItemWastageId,
                    'spareParts' => $spareParts,
                    'checkWastageStatus' => $checkWastageStatus,
                    'availableQuantity' => $availableQuantity
                ));
            }else{
                $form = $this->createForm(SetWastageSoldType::class,[],array(
                    'action' => $this->generateUrl('liquid_wastage_sold'),
                ));
                $formHtml = $this->renderView('liquid_parts_details/modify.html.twig', array(
                    'form' => $form->createView(),
                    'liquidPartsDetails'=> $liquidPartsDetails,
                    'liquidItemWastageId'=>$liquidItemWastageId,
                    'spareParts' => $spareParts,
                    'checkWastageStatus' => $checkWastageStatus,
                    'availableQuantity' => $availableQuantity
                ));
            }
            return new JsonResponse(
                array(
                    'success' => true,
                    'formHtml' => $formHtml
                )
            );
        }

    }


    /**
     * @Route("/liquid/wastage/{id}/delete", name="liquid_wastage_delete",  methods={"GET"})
     * @param Request $request
     * @param string $id
     * @param LiquidPartsDetailsRepository $liquidPartsDetailsRepository
     * @return RedirectResponse
     */
    public function deleteContra( Request $request, string $id, LiquidPartsDetailsRepository $liquidPartsDetailsRepository)
    {
        $liquidPartsDetails = $liquidPartsDetailsRepository->find($id);
        if($liquidPartsDetails=="" || empty($liquidPartsDetails)){
            $this->addFlash(
                'notice',
                'Please Try Again '
            );
            return $this->redirectToRoute('liquid_parts_details_index');
        }
        $liquidPartsDetails->setStatus(0);
        $entity = $this->getDoctrine()->getManager();
        $entity->persist($liquidPartsDetails);
        $entity->flush();
        $this->addFlash(
            'success',
            'Delete Process Successfully Complete'
        );
        return $this->redirectToRoute('liquid_parts_details_index');
    }



    /**
     * @Route("/{id}", name="liquid_parts_details_show", methods={"GET"})
     */
    public function show(LiquidPartsDetails $liquidPartsDetail): Response
    {
        return $this->render('liquid_parts_details/show.html.twig', [
            'liquid_parts_detail' => $liquidPartsDetail,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="liquid_parts_details_edit", methods={"GET","POST"})
     * @param Request $request
     * @param LiquidPartsDetails $liquidPartsDetail
     * @return Response
     */
    public function edit(Request $request, LiquidPartsDetails $liquidPartsDetail): Response
    {
        //die('I am here');
        $form = $this->createForm(LiquidPartsDetailsType::class, $liquidPartsDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('liquid_parts_details_index');
        }

        return $this->render('liquid_parts_details/edit.html.twig', [
            'liquid_parts_detail' => $liquidPartsDetail,
//            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="liquid_parts_details_delete", methods={"DELETE"})
     */
    public function delete(Request $request, LiquidPartsDetails $liquidPartsDetail): Response
    {
        if ($this->isCsrfTokenValid('delete'.$liquidPartsDetail->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($liquidPartsDetail);
            $entityManager->flush();
        }

        return $this->redirectToRoute('liquid_parts_details_index');
    }
}
