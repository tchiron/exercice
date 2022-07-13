<?php

namespace App\Controller;

use App\Entity\Kilometrage;
use App\Form\KilometrageType;
use App\Repository\KilometrageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/kilometrage')]
class KilometrageController extends AbstractController
{
    #[Route('/', name: 'app_kilometrage_index', methods: ['GET'])]
    public function index(KilometrageRepository $kilometrageRepository): Response
    {
        return $this->render('kilometrage/index.html.twig', [
            'kilometrages' => $kilometrageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_kilometrage_new', methods: ['GET', 'POST'])]
    public function new(Request $request, KilometrageRepository $kilometrageRepository): Response
    {
        $kilometrage = new Kilometrage();
        $form = $this->createForm(KilometrageType::class, $kilometrage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $kilometrage->setCreatedAt(new \DateTime())->setUser($this->getUser());
            $kilometrageRepository->add($kilometrage, true);

            return $this->redirectToRoute('app_dashboard', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('kilometrage/new.html.twig', [
            'kilometrage' => $kilometrage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_kilometrage_show', methods: ['GET'])]
    public function show(Kilometrage $kilometrage): Response
    {
        return $this->render('kilometrage/show.html.twig', [
            'kilometrage' => $kilometrage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_kilometrage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Kilometrage $kilometrage, KilometrageRepository $kilometrageRepository): Response
    {
        $form = $this->createForm(KilometrageType::class, $kilometrage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $kilometrageRepository->add($kilometrage, true);

            return $this->redirectToRoute('app_kilometrage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('kilometrage/edit.html.twig', [
            'kilometrage' => $kilometrage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_kilometrage_delete', methods: ['POST'])]
    public function delete(Request $request, Kilometrage $kilometrage, KilometrageRepository $kilometrageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$kilometrage->getId(), $request->request->get('_token'))) {
            $kilometrageRepository->remove($kilometrage, true);
        }

        return $this->redirectToRoute('app_kilometrage_index', [], Response::HTTP_SEE_OTHER);
    }
}
