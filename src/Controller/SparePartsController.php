<?php

namespace App\Controller;

use App\Entity\EntryDetails;
use App\Entity\SpareParts;
use App\Form\SparePartsType;
use App\Repository\SparePartsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/spare/parts")
 * @IsGranted("ROLE_ADMIN")
 */
class SparePartsController extends AbstractController
{
    /**
     * @Route("/", name="spare_parts_index", methods={"GET"})
     * @param SparePartsRepository $sparePartsRepository
     * @return Response
     */
    public function index(SparePartsRepository $sparePartsRepository): Response
    {
        return $this->render('spare_parts/index.html.twig', [
            'spare_parts' => $sparePartsRepository->findAll(),
        ]);
    }


    /**
     * @Route("/detail/json", name="spare_parts_details", methods={"GET","POST"})
     * @param SparePartsRepository $sparePartsRepository
     * @param Request $request
     * @return JsonResponse
     */
    public function getSparePartDetailsJson(SparePartsRepository $sparePartsRepository,Request $request): JsonResponse
    {
        try{
       $id = $request->request->get('id');
       $sparePart = $sparePartsRepository->find($id);
       $responseArr = [];
       $id = $sparePart->getId();
       $name = $sparePart->getName();
       $mileage = $sparePart->getMileage();
       $isTire = $sparePart->getIsTire();
       $NumOrQty = $sparePart->getNumOrQty();
       $status = $sparePart->getStatus();

        $responseArr = ['id'=>$id, 'name' => $name, 'isMileage'=> $mileage, 'isTire'=> $isTire, 'numOrQty'=> $NumOrQty, 'status'=> $status];


       $response = new JsonResponse($responseArr);
       return $response;
        }catch (\Exception $ex) {
            return new JsonResponse();
        }

    }

    /**
     * @Route("/car/tire/json", name="car_detail_details", methods={"GET","POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getCarEmptyTirePosition(Request $request):JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        try{
            $car = $request->request->get('car');
            $spare = $request->request->get('tire');
            $enRipo = $em->getRepository(EntryDetails::class);
            $data = $enRipo->findBy(['car'=>$car,'spareParts'=>$spare,'status'=>1]);
            $response = [];
            foreach ($data as $d){
                array_push($response,$d->getTirePosition());
            }

            return new JsonResponse($response);

        }catch(\Exception $ex){
            return new JsonResponse();
        }

    }




    /**
     * @Route("/new", name="spare_parts_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $sparePart = new SpareParts();
        $form = $this->createForm(SparePartsType::class, $sparePart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sparePart);
            $entityManager->flush();

            return $this->redirectToRoute('spare_parts_index');
        }

        return $this->render('spare_parts/new.html.twig', [
            'spare_part' => $sparePart,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="spare_parts_show", methods={"GET"})
     * @param SpareParts $sparePart
     * @return Response
     */
    public function show(SpareParts $sparePart): Response
    {
        return $this->render('spare_parts/show.html.twig', [
            'spare_part' => $sparePart,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="spare_parts_edit", methods={"GET","POST"})
     * @param Request $request
     * @param SpareParts $sparePart
     * @return Response
     */
    public function edit(Request $request, SpareParts $sparePart): Response
    {
        $form = $this->createForm(SparePartsType::class, $sparePart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('spare_parts_index');
        }

        return $this->render('spare_parts/edit.html.twig', [
            'spare_part' => $sparePart,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="spare_parts_delete", methods={"DELETE"})
     * @param Request $request
     * @param SpareParts $sparePart
     * @return Response
     */
    public function delete(Request $request, SpareParts $sparePart): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sparePart->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sparePart);
            $entityManager->flush();
        }

        return $this->redirectToRoute('spare_parts_index');
    }
}
