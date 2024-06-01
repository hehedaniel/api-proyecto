<?php

namespace App\Controller;

use App\Entity\ConsumoDia;
use App\Form\ConsumoDiaType;
use App\Repository\ConsumoDiaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Util\RespuestaController;

/**
 * @Route("/consumo_dia")
 */
class ConsumoDiaController extends AbstractController
{
    /**
     * @Route("/usuario/{id}", name="consumo_dia_usuario", methods={"GET"})
     */
    public function getByUsuario(ConsumoDiaRepository $consumoDiaRepository, $id): Response
    {
        $consumoDia = $consumoDiaRepository->findByUsuario($id);

        return $this->json($consumoDia);
    }

    /**
     * @Route("/usuario/rango/{id}", name="consumo_dia_usuario_fechas", methods={"GET"})
     */
    public function getByUsuarioAndFechas(Request $request, ConsumoDiaRepository $consumoDiaRepository, $id): Response
    {
        $data = json_decode($request->getContent(), true);

        $fechaInicio = new \DateTime($data['fechaInicio']);
        $fechaFin = new \DateTime($data['fechaFin']);

        $consumosDia = $consumoDiaRepository->findByUsuario($id);

        $consumosDia = array_filter($consumosDia, function ($consumoDia) use ($fechaInicio, $fechaFin) {
            return $consumoDia->isFechaBetween($fechaInicio, $fechaFin);
        });

        if (!$consumosDia) {
            return RespuestaController::format("404", "No se encontraron entradas en las fechas indicadas.");
        }

        return $this->json($consumosDia);
    }

    /**
     * @Route("/crear/usuario/{id}", name="consumo_dia_usuario", methods={"POST"})
     */
    public function crear(Request $request, ConsumoDiaRepository $consumoDiaRepository, $id): Response
    {
        $data = json_decode($request->getContent(), true);

        $consumoDia = new ConsumoDia();
        $consumoDia->setComida($data['comida']);
        $consumoDia->setCantidad($data['cantidad']);
        $consumoDia->setMomento($data['momento']);
        $consumoDia->setFecha($data['fecha']);
        $consumoDia->setHora($data['hora']);
        $consumoDia->setIdUsuario($id);

        $consumoDiaRepository->add($consumoDia, true);

        $consumoDiaJSON = $this->consumoDiaJSON($consumoDia);

        return RespuestaController::format("200", $consumoDiaJSON);
    }

    /**
     * @Route("/editar", name="editar_consumo_dia_usuario", methods={"PUT"})
     */
    public function editar(Request $request, ConsumoDiaRepository $consumoDiaRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        $consumoDia = $consumoDiaRepository->findOneBy([
            'fecha' => new \DateTime($data['fecha']),
            'idUsuario' => $data['idUsuario']
        ]);

        if (!$consumoDia) {
            return RespuestaController::format("404", "No se encontró la entrada correspondiente.");
        }

        $consumoDia->setComida($data['comida']);
        $consumoDia->setCantidad($data['cantidad']);
        $consumoDia->setMomento($data['momento']);
        $consumoDia->setFecha(new \DateTime($data['fecha']));
        $consumoDia->setHora(new \DateTime($data['hora']));
        $consumoDia->setIdUsuario($data['idUsuario']);

        $consumoDiaRepository->add($consumoDia, true);

        $consumoDiaJSON = $this->consumoDiaJSON($consumoDia);

        return RespuestaController::format("200", $consumoDiaJSON);
    }

    /**
     * @Route("/eliminar", name="eliminar_consumo_dia_usuario", methods={"DELETE"})
     */
    public function eliminar(Request $request, ConsumoDiaRepository $consumoDiaRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        $consumoDia = $consumoDiaRepository->findOneBy([
            'fecha' => new \DateTime($data['fecha']),
            'idUsuario' => $data['idUsuario']
        ]);

        if (!$consumoDia) {
            return RespuestaController::format("404", "No se encontró la entrada correspondiente.");
        }

        $consumoDiaRepository->remove($consumoDia, true);

        return RespuestaController::format("200", "Entrada eliminada correctamente.");
    }


    private function consumoDiaJSON(ConsumoDia $consumoDia)
    {

        $consumoDiaJSON = [
            "id" => $consumoDia->getId(),
            "comida" => $consumoDia->getComida(),
            "cantidad" => $consumoDia->getCantidad(),
            "momento" => $consumoDia->getMomento(),
            "fecha" => $consumoDia->getFecha(),
            "hora" => $consumoDia->getHora(),
            "idUsuario" => $consumoDia->getIdUsuario(),
        ];

        return $consumoDiaJSON;
    }

}