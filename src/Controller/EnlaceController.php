<?php

namespace App\Controller;

use App\Entity\Enlace;
use App\Form\EnlaceType;
use App\Repository\EnlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/enlace")
 */
class EnlaceController extends AbstractController
{
    /**
     * @Route("/", name="app_enlace_index", methods={"GET"})
     */
    public function index(EnlaceRepository $enlaceRepository): Response
    {
        return $this->render('enlace/index.html.twig', [
            'enlaces' => $enlaceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_enlace_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EnlaceRepository $enlaceRepository): Response
    {
        $enlace = new Enlace();
        $form = $this->createForm(EnlaceType::class, $enlace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $enlaceRepository->add($enlace, true);

            return $this->redirectToRoute('app_enlace_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('enlace/new.html.twig', [
            'enlace' => $enlace,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_enlace_show", methods={"GET"})
     */
    public function show(Enlace $enlace): Response
    {
        return $this->render('enlace/show.html.twig', [
            'enlace' => $enlace,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_enlace_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Enlace $enlace, EnlaceRepository $enlaceRepository): Response
    {
        $form = $this->createForm(EnlaceType::class, $enlace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $enlaceRepository->add($enlace, true);

            return $this->redirectToRoute('app_enlace_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('enlace/edit.html.twig', [
            'enlace' => $enlace,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_enlace_delete", methods={"POST"})
     */
    public function delete(Request $request, Enlace $enlace, EnlaceRepository $enlaceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$enlace->getId(), $request->request->get('_token'))) {
            $enlaceRepository->remove($enlace, true);
        }

        return $this->redirectToRoute('app_enlace_index', [], Response::HTTP_SEE_OTHER);
    }
}
