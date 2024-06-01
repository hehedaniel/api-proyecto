<?php

namespace App\Controller;

use App\Entity\Ejercicio;
use App\Repository\EjercicioRepository;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Util\RespuestaController;

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
        $ejercicios = $ejercicioRepository->findAll();

        if (!$ejercicios) {
            return RespuestaController::format("404", "No hay ejercicios registrados");
        }

        $ejerciciosJSON = [];

        foreach ($ejercicios as $ejercicio) {
            $ejerciciosJSON[] = $this->ejercicioJSON($ejercicio);
        }

        return RespuestaController::format("200", $ejerciciosJSON);
    }

    /**
     * @Route("/{id}", name="app_ejercicio_buscar", methods={"GET"})
     */
    public function buscar($id, EjercicioRepository $ejercicioRepository): Response
    {
        $ejercicio = $ejercicioRepository->find($id);

        if (!$ejercicio) {
            return RespuestaController::format("404", "Ejercicio no encontrado");
        }

        $ejercicioJSON = $this->ejercicioJSON($ejercicio);

        return RespuestaController::format("200", $ejercicioJSON);
    }

    /**
     * @Route("/crear", name="app_ejercicio_crear", methods={"POST"})
     */
    public function crear(Request $request, EjercicioRepository $ejercicioRepository, UsuarioRepository $usuasrioRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return RespuestaController::format("400", "No se han recibido datos");
        }

        $existingEjercicio = $ejercicioRepository->findOneBy(['nombre' => $data['nombre']]);
        if ($existingEjercicio) {
            return RespuestaController::format("400", "Ya existe un ejercicio con el mismo nombre");
        }

        $ejercicio = new Ejercicio();
        $ejercicio->setNombre($data['nombre']);
        $ejercicio->setDescripcion($data['descripcion']);
        $ejercicio->setGrupoMuscular($data['grupoMuscular']);
        $ejercicio->setDificultad($data['dificultad']);
        $ejercicio->setInstrucciones($data['instrucciones']);
        $ejercicio->setValorMET($data['valorMET']);

        $ejercicio->setIdUsuario($usuasrioRepository->find($data['idUsuario']));

        $ejercicioRepository->add($ejercicio, true);

        $ejercicioJSON = $this->ejercicioJSON($ejercicio);

        return RespuestaController::format("200", $ejercicioJSON);
    }

    /**
     * @Route("/editar/{id}", name="app_ejercicio_editar", methods={"PUT"})
     */
    public function editar($id, Request $request, EjercicioRepository $ejercicioRepository, UsuarioRepository $usuasrioRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return RespuestaController::format("400", "No se han recibido datos");
        }

        $ejercicio = $ejercicioRepository->find($id);

        if (!$ejercicio) {
            return RespuestaController::format("404", "Ejercicio no encontrado");
        }

        $ejercicio->setNombre($data['nombre']);
        $ejercicio->setDescripcion($data['descripcion']);
        $ejercicio->setGrupoMuscular($data['grupoMuscular']);
        $ejercicio->setDificultad($data['dificultad']);
        $ejercicio->setInstrucciones($data['instrucciones']);
        $ejercicio->setValorMET($data['valorMET']);
        $ejercicio->setIdUsuario($usuasrioRepository->find($data['idUsuario']));

        $ejercicioRepository->add($ejercicio, true);

        $ejercicioJSON = $this->ejercicioJSON($ejercicio);

        return RespuestaController::format("200", $ejercicioJSON);
    }

    /**
     * @Route("/eliminar", name="app_ejercicio_eliminar", methods={"DELETE"})
     */
    public function eliminar(Request $request, EjercicioRepository $ejercicioRepository): Response
    {

        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return RespuestaController::format("400", "No se han recibido datos");
        }

        if (isset($data['id'])) {
            // Buscar ejercicio por ID
            $ejercicio = $ejercicioRepository->find($data['id']);
        }else {
            return RespuestaController::format("400", "ID no recibidos");
        }

        if (!$ejercicio) {
            return RespuestaController::format("404", "Ejercicio no encontrado");
        }

        $ejercicioRepository->remove($ejercicio, true);

        return RespuestaController::format("200", "Ejercicio eliminado correctamente");
    }

    // Función para convertir un objeto Ejercicio a formato JSON
    private function ejercicioJSON(Ejercicio $ejercicio)
    {
        $ejercicioJSON = [
            "id" => $ejercicio->getId(),
            "nombre" => $ejercicio->getNombre(),
            "descripcion" => $ejercicio->getDescripcion(),
            "grupoMuscular" => $ejercicio->getGrupoMuscular(),
            "dificultad" => $ejercicio->getDificultad(),
            "instrucciones" => $ejercicio->getInstrucciones(),
            "valorMET" => $ejercicio->getValorMET(),
            "idUsuario" => $ejercicio->getIdUsuario()->getId()
        ];

        return $ejercicioJSON;
    }
}
