<?php

namespace App\Controller;

use App\Entity\Peso;
use App\Form\PesoType;
use App\Repository\PesoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/peso")
 */
class PesoController extends AbstractController
{
    /**
     * @Route("/", name="app_peso_index", methods={"GET"})
     */
    public function index(PesoRepository $pesoRepository): Response
    {
        return $this->render('peso/index.html.twig', [
            'pesos' => $pesoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_peso_new", methods={"GET", "POST"})
     */
    public function new(Request $request, PesoRepository $pesoRepository): Response
    {
        $peso = new Peso();
        $form = $this->createForm(PesoType::class, $peso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pesoRepository->add($peso, true);

            return $this->redirectToRoute('app_peso_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('peso/new.html.twig', [
            'peso' => $peso,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_peso_show", methods={"GET"})
     */
    public function show(Peso $peso): Response
    {
        return $this->render('peso/show.html.twig', [
            'peso' => $peso,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_peso_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Peso $peso, PesoRepository $pesoRepository): Response
    {
        $form = $this->createForm(PesoType::class, $peso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pesoRepository->add($peso, true);

            return $this->redirectToRoute('app_peso_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('peso/edit.html.twig', [
            'peso' => $peso,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_peso_delete", methods={"POST"})
     */
    public function delete(Request $request, Peso $peso, PesoRepository $pesoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$peso->getId(), $request->request->get('_token'))) {
            $pesoRepository->remove($peso, true);
        }

        return $this->redirectToRoute('app_peso_index', [], Response::HTTP_SEE_OTHER);
    }
}
