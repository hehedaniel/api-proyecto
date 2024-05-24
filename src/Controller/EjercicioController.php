<?php

namespace App\Controller;

use App\Entity\Ejercicio;
use App\Form\EjercicioType;
use App\Repository\EjercicioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ejercicio")
 */
class EjercicioController extends AbstractController
{
    /**
     * @Route("/", name="app_ejercicio_index", methods={"GET"})
     */
    public function index(EjercicioRepository $ejercicioRepository): Response
    {
        return $this->render('ejercicio/index.html.twig', [
            'ejercicios' => $ejercicioRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_ejercicio_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EjercicioRepository $ejercicioRepository): Response
    {
        $ejercicio = new Ejercicio();
        $form = $this->createForm(EjercicioType::class, $ejercicio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ejercicioRepository->add($ejercicio, true);

            return $this->redirectToRoute('app_ejercicio_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ejercicio/new.html.twig', [
            'ejercicio' => $ejercicio,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ejercicio_show", methods={"GET"})
     */
    public function show(Ejercicio $ejercicio): Response
    {
        return $this->render('ejercicio/show.html.twig', [
            'ejercicio' => $ejercicio,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_ejercicio_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Ejercicio $ejercicio, EjercicioRepository $ejercicioRepository): Response
    {
        $form = $this->createForm(EjercicioType::class, $ejercicio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ejercicioRepository->add($ejercicio, true);

            return $this->redirectToRoute('app_ejercicio_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ejercicio/edit.html.twig', [
            'ejercicio' => $ejercicio,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_ejercicio_delete", methods={"POST"})
     */
    public function delete(Request $request, Ejercicio $ejercicio, EjercicioRepository $ejercicioRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ejercicio->getId(), $request->request->get('_token'))) {
            $ejercicioRepository->remove($ejercicio, true);
        }

        return $this->redirectToRoute('app_ejercicio_index', [], Response::HTTP_SEE_OTHER);
    }
}
