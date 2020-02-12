<?php

namespace App\Controller;

use App\Entity\EntryDetails;
use App\Form\CalenderType;
use App\Form\EntryDetailsType;
use App\Form\EntryReportType;
use App\Form\SolidSoldType;
use App\Form\SolidWastageType;
use App\Repository\EntryDetailsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/entry/details")
 * @IsGranted("ROLE_ADMIN")
 */
class EntryDetailsController extends AbstractController
{
    /**
     * @Route("/", name="entry_details_index")
     * @param EntryDetailsRepository $entryDetailsRepository
     * @param Request $request
     * @return Response
     */
    public function index(EntryDetailsRepository $entryDetailsRepository, Request $request): Response
    {
        //$entryDetails = $entryDetailsRepository->findBy(['tirePosition'=>null]);
        $entryDetails = '';
        $spareParts = '';
        $statusType = '1';
        $car = '';
        $findFromDate = date("m/d/y");
        $findToDate = date("m/d/y");
        $form = $this->createForm(EntryReportType::class);
        $formData = $request->request->all();
        if ($request->isMethod('POST')) {
            $findFromDate = ($formData['entry_report']['findFromDate']!="")?$formData['entry_report']['findFromDate']:date("m/d/y");
            $findToDate = ($formData['entry_report']['findToDate']!="")?$formData['entry_report']['findToDate']:date("m/d/y");
            $spareParts = ($formData['entry_report']['spareParts']!="")?$formData['entry_report']['spareParts']:'';
            $statusType = ($formData['entry_report']['statusType']!="")?$formData['entry_report']['statusType']:'1';
            $car        = ($formData['entry_report']['car']!="")?$formData['entry_report']['car']:'';
//            $isLiquidType = $this->getDoctrine()->getRepository('App:SpareParts')->find($spareParts);
//            if($isLiquidType!=""){
//                $isLiquidType = $isLiquidType->getNumOrQty();
//            }
//            if($isLiquidType=='2'){
//                $this->addFlash(
//                    'notice',
//                    'Sorry Go To Liquid Panel '
//                );
//                return $this->redirectToRoute('entry_details_index');
//            }
            //dump($isLiquidType);die();
        }

        $entryDetails = $entryDetailsRepository->getSpearsPartsListByCondition($findFromDate, $findToDate, $spareParts, $statusType, $car );
        //dump($entryDetails);die();
        return $this->render('entry_details/index.html.twig', [
            'form' => $form->createView(),
            'findFromDate' => $findFromDate,
            'findToDate' => $findToDate,
            'entry_details' => $entryDetails,
            'spareParts'    => $spareParts,
            'statusType'    => $statusType,
            'car'    => $car
        ]);
    }

    /**
     * @Route("/new", name="entry_details_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $entryDetail = new EntryDetails();
        $form = $this->createForm(EntryDetailsType::class, $entryDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() ) {
            $entityDetailList = [];
            $entityManager = $this->getDoctrine()->getManager();
            $car = $entryDetail->getCar();
            $sparePart = $entryDetail->getSpareParts();
            $user = $this->getUser();
            $time = time();
            if($sparePart->getNumOrQty() == 1)
            {
                $qty = (int)$entryDetail->getQty();
                for($i = 1; $i<=$qty; $i++){
                    $soleEntity = new EntryDetails();
                    $soleEntity->setCreateAt(new \DateTime());
                    $soleEntity->setCar($car);
                    $soleEntity->setSpareParts($sparePart);
                    $soleEntity->setStatus(1);
                    $soleEntity->setIsQuantityNumber(1);
                    $soleEntity->setStartMillage(0);
                    $soleEntity->setUser($user);
                    $soleEntity->setSlug($time);
                    if($sparePart->getIsTire()){
//                        For Tire type data this code will work for this
                        $soleEntity->setIsTire(true);
                        if($i == 1) {
                            $soleEntity->setTirePosition($request->request->get('entry_details')['tirePosition']);
                            $soleEntity->setPrice($request->request->get('entry_details')['price']);
                            $soleEntity->setPartsDescription($request->request->get('entry_details')['partsDescription']);
                        }
                        else {
                            $soleEntity->setTirePosition($request->request->get('entry_details')['tirePosition_'.$i]);
                            $soleEntity->setPrice($request->request->get('entry_details')['price_'.$i]);
                            $soleEntity->setPartsDescription($request->request->get('entry_details')['partsDescription_'.$i]);
                        }
                    }else
                    {
                        if($i == 1) {
//                            this is for the first Number Data
                            $soleEntity->setPrice($request->request->get('entry_details')['price']);
                            $soleEntity->setPartsDescription($request->request->get('entry_details')['partsDescription']);
                        }
                        else {
                            $soleEntity->setPrice($request->request->get('entry_details')['price_'.$i]);
                            $soleEntity->setPartsDescription($request->request->get('entry_details')['partsDescription_'.$i]);
                        }
                    }
                    $entityManager->persist($soleEntity);
                }


            }
            else {
                $entryDetail->setSlug($time);
                $entryDetail->setCreateAt(new \DateTime());
                $entryDetail->setStatus(1);
                $entryDetail->setIsQuantityNumber(0);
                $entryDetail->setStartMillage(0);
                $entryDetail->setUser($user);
                $entityManager->persist($entryDetail);


            }

            $entityManager->flush();
            return $this->redirectToRoute('entry_details_index');
        }

        return $this->render('entry_details/new.html.twig', [
            'entry_detail' => $entryDetail,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="entry_details_show", methods={"GET"})
     */
    public function show(EntryDetails $entryDetail): Response
    {
        return $this->render('entry_details/show.html.twig', [
            'entry_detail' => $entryDetail,
        ]);
    }


    /**
     * @Route("/makeWastage/{id}", name="make_wastage", methods={"POST"})
     * @param EntryDetailsRepository $entryDetailsRepository
     * @param Request $request
     * @return JsonResponse
     */
    public function showForm( EntryDetailsRepository $entryDetailsRepository, Request $request): JsonResponse
    {
        if ($request->isXmlHttpRequest()) {
            $entryDetailsId = $request->request->get('id');
            $actionStatus = $request->request->get('actionStatus');

            $entryDetails = $entryDetailsRepository->find($entryDetailsId);
            $isMillage = $entryDetails->getSpareParts()->getMileage();
           ;
            if($actionStatus=='1'){
                $form = $this->createForm(SolidWastageType::class,[],array(
                    'action' => $this->generateUrl('wastage_modify'),
                ));
                $formHtml = $this->renderView('entry_details/make_wastage.twig', array(
                    'form' => $form->createView(),
                    'entryDetailsId'=>$entryDetailsId,
                    'isMillage' => $isMillage,
                    'actionStatus'=> $actionStatus
                ));
            }else{
                $form = $this->createForm(SolidSoldType::class,[],array(
                    'action' => $this->generateUrl('wastage_modify'),
                ));
                $formHtml = $this->renderView('entry_details/sold.twig', array(
                    'form' => $form->createView(),
                    'entryDetailsId'=>$entryDetailsId,
                    'actionStatus'=> $actionStatus
                ));
            }

            return new JsonResponse(
                array(
                    'success' => true,
                    'formHtml' => $formHtml
                )
            );
        }

//        return $this->render('transaction/show.html.twig', [
//            'transactions' => $data,
//        ]);
    }


    /**
     * @Route("/action/wastage", name="wastage_modify", methods={"GET","POST"})
     * @param EntryDetailsRepository $entryDetailsRepository
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateWastage(  EntryDetailsRepository $entryDetailsRepository,  Request $request)
    {

        $data = $request->request->all();
        if ($request->isMethod('POST')) {
            // dump($data);die();

            $actionStatus = isset($data['solid_wastage']['actionStatus'])?$data['solid_wastage']['actionStatus']:$data['solid_sold']['actionStatus'];

            //$actionStatus = $data['solid_wastage']['actionStatus'];


            if($actionStatus == '1'){
                $entryDetailsID = $data['solid_wastage']['entryDetails'];
                if($entryDetailsID == ""){
                    $this->addFlash(
                        'notice',
                        'Please Enter Valid Data'
                    );
                    return $this->redirectToRoute('entry_details_index');
                }
                $entryDetails = $entryDetailsRepository->find($entryDetailsID);
                if(isset($data['solid_wastage']['endMillage']) && $data['solid_wastage']['endMillage']!=""){
                    $entryDetails->setEndMillage($data['solid_wastage']['endMillage']);
                }
                $entryDetails->setStatus(2);
                $entryDetails->setWastageDate(new \DateTime());
            }else{
                $entryDetailsID = $data['solid_sold']['entryDetails'];
                $entryDetails = $entryDetailsRepository->find($entryDetailsID);
                if($data['solid_sold']['soldPrice']=="" || $entryDetailsID==""){
                    $this->addFlash(
                        'notice',
                        'Please Enter Valid Data'
                    );
                    return $this->redirectToRoute('entry_details_index');
                }
                $entryDetails->setSoldPrice($data['solid_sold']['soldPrice']);
                $entryDetails->setStatus(3);
                $entryDetails->setSoldDate(new \DateTime());
            }
            $this->getDoctrine()->getManager()->flush();
            //echo $entryDetails;die();
            $this->addFlash(
                'success',
                'Make Wastage Successfully Completed'
            );
            return $this->redirectToRoute('entry_details_index');
        }else{
            $this->addFlash(
                'notice',
                'Please Enter Valid Data'
            );
            return $this->redirectToRoute('entry_details_index');
        }

    }


    /**
     * @Route("/{id}/edit", name="entry_details_edit", methods={"GET","POST"})
     * @param Request $request
     * @param string $id
     * @return Response
     */
    public function edit(Request $request, string $id): Response
    {
        $em = $this->getDoctrine()->getManager();
//        try{

            $entryDetail = $em->getRepository(EntryDetails::class)->find($id);

            $form = $this->createForm(EntryDetailsType::class, $entryDetail);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('entry_details_index');
            }
            return $this->render('entry_details/edit.html.twig', [
                'entry_detail' => $entryDetail,
                'form' => $form->createView(),
            ]);

//
//        }catch (\Exception $e){
//                $this->addFlash('notice',$e->getMessage());
//            return $this->redirectToRoute('entry_details_edit',['id'=>$id]);
//        }

    }

    /**
     * @Route("/{id}", name="entry_details_delete", methods={"DELETE"})
     */
    public function delete(Request $request, EntryDetails $entryDetail): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entryDetail->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($entryDetail);
            $entityManager->flush();
        }

        return $this->redirectToRoute('entry_details_index');
    }


    /**
     * @Route("/sparepart/json", name="sparepart_json", methods={"GET","POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function sparepart_json(Request $request):JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        try{
            $id = $request->request->get('id');
            $entryDetails = $em->getRepository(EntryDetails::class)->find($id);
            $tirePosition = null;
            if($entryDetails->getIsTire())
            {
                $tirePosition = $entryDetails->getTirePosition();
            }
            Return new JsonResponse(['tirePosition'=> $tirePosition]);

        }catch (\Exception $e){
            Return new JsonResponse(['tirePosition'=> null]);
        }
    }

    /**
     * @Route("/delete/entry/{id}",name ="delete_entry_detail", methods={"GET","POST"})
     * @param Request $request
     * @param string $id
     * @return Response
     */
    public function deleteItemByStatus(Request $request, string $id):Response
    {
        $em = $this->getDoctrine()->getManager();
        try{
            $entryDetails = $em->getRepository(EntryDetails::class)->find($id);
            $entryDetails->setStatus(0);
            $em->flush();
            $this->addFlash('notice', $entryDetails->getSpareParts()->getName().' deleted successfully');
            return $this->redirectToRoute('entry_details_index');
        }catch (\Exception $e){

        }

    }

}
