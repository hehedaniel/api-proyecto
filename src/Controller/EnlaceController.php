<?php

namespace App\Controller;

use App\Entity\Enlace;
use App\Repository\EnlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Util\RespuestaController;
use App\Repository\EjercicioRepository;

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
        $enlaces = $enlaceRepository->findAll();

        if (!$enlaces) {
            return RespuestaController::format("404", "No hay enlaces registrados");
        }

        $enlacesJSON = [];

        foreach ($enlaces as $enlace) {
            $enlacesJSON[] = [
                "id" => $enlace->getId(),
                "enlace" => $enlace->getEnlace(),
                "idEjercicio" => $enlace->getIdEjercicio()->getId(),
            ];
        }

        return RespuestaController::format("200", $enlacesJSON);
    }

    /**
     * @Route("/{id}", name="app_enlace_buscar", methods={"GET"})
     */
    public function buscar($id, EnlaceRepository $enlaceRepository): Response
    {
        $enlace = $enlaceRepository->find($id);

        if (!$enlace) {
            return RespuestaController::format("404", "Enlace no encontrado");
        }

        $enlaceJSON = $this->enlaceJSON($enlace);

        return RespuestaController::format("200", $enlaceJSON);
    }

    /**
     * @Route("/new", name="app_enlace_new", methods={"POST"})
     */
    public function new(Request $request, EnlaceRepository $enlaceRepository, EjercicioRepository $ejercicioRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return RespuestaController::format("400", "No se han recibido datos");
        }

        $enlace = new Enlace();
        $enlace->setEnlace($data['enlace']);

        // Buscar el ejercicio por ID
        $ejercicio = $ejercicioRepository->find($data['idEjercicio']);
        if (!$ejercicio) {
            return RespuestaController::format("400", "Ejercicio no encontrado");
        }
        $enlace->setIdEjercicio($ejercicio);

        $enlaceRepository->add($enlace, true);

        $enlaceJSON = $this->enlaceJSON($enlace);

        return RespuestaController::format("200", $enlaceJSON);
    }

    /**
     * @Route("/edit/{id}", name="app_enlace_edit", methods={"PUT"})
     */
    public function edit($id, Request $request, EnlaceRepository $enlaceRepository, EjercicioRepository $ejercicioRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return RespuestaController::format("400", "No se han recibido datos");
        }

        // Buscar enlace a editar por ID
        $enlace = $enlaceRepository->find($id);

        if (!$enlace) {
            return RespuestaController::format("404", "Enlace a editar no encontrado");
        }

        // Parámetros a recibir
        $enlace->setEnlace($data['enlace']);

        // Buscar el ejercicio por ID
        $ejercicio = $ejercicioRepository->find($data['idEjercicio']);
        if (!$ejercicio) {
            return RespuestaController::format("400", "Datos no modificados: Ejercicio no encontrado");
        }
        $enlace->setIdEjercicio($ejercicio);

        $enlaceRepository->add($enlace, true);

        $enlaceJSON = $this->enlaceJSON($enlace);

        return RespuestaController::format("200", $enlaceJSON);
    }

    /**
     * @Route("/delete", name="app_enlace_delete", methods={"DELETE"})
     */
    public function delete(Request $request, EnlaceRepository $enlaceRepository): Response
    {

        // if ($request->getMethod() !== 'DELETE') {
        //     return RespuestaController::format("405", "Método no permitido");
        // }

        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return RespuestaController::format("400", "No se han recibido datos");
        }

        if (isset($data['id'])) {
            // Buscar enlace por ID
            $enlace = $enlaceRepository->find($data['id']);
        } else {
            return RespuestaController::format("400", "ID no proporcionado");
        }

        if (!$enlace) {
            return RespuestaController::format("404", "Enlace no existente");
        }

        $enlaceRepository->remove($enlace, true);

        return RespuestaController::format("200", "Enlace eliminado correctamente");
    }

    private function enlaceJSON(Enlace $enlace)
    {
        $enlaceJSON = [
            "id" => $enlace->getId(),
            "enlace" => $enlace->getEnlace(),
            "idEjercicio" => $enlace->getIdEjercicio()->getId(),
        ];

        return $enlaceJSON;
    }


}
