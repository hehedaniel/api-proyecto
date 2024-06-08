<?php

namespace App\Controller;

use App\Entity\ConsumoDia;
use App\Form\ConsumoDiaType;
use App\Repository\AlimentoRepository;
use App\Entity\Alimento;
use App\Repository\ConsumoDiaRepository;
use App\Repository\RecetasRepository;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Util\RespuestaController;
use App\Util\CbbddConsultas;

/**
 * @Route("/consumodia")
 */
class ConsumoDiaController extends AbstractController
{
    /**
     * @Route("/usuario/{id}", name="consumo_dia_usuario_getByUsuario", methods={"GET"})
     */
    public function getByUsuario(ConsumoDiaRepository $consumoDiaRepository, $id): Response
    {
        $consumoDiaUsuario = $consumoDiaRepository->findBy(['idUsuario' => $id]);

        if (!$consumoDiaUsuario) {
            return RespuestaController::format("404", "No se encontraron entradas.");
        }

        foreach ($consumoDiaUsuario as $consumoDia) {
            $consumosDiaJSON[] = $this->consumoDiaJSON($consumoDia);
        }

        return RespuestaController::format("200", $consumosDiaJSON);

    }

    /**
     * @Route("/usuario/rango/{id}", name="consumo_dia_usuario_fechas", methods={"POST"})
     */
    public function getByUsuarioAndFechas(Request $request, ConsumoDiaRepository $consumoDiaRepository, $id, AlimentoRepository $alimentoRepository, RecetasRepository $recetasRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        $fechaInicio = new \DateTime($data['fechaInicio']);
        $fechaFin = new \DateTime($data['fechaFin']);

        $consumosDia = $consumoDiaRepository->findBy(['idUsuario' => $id]);

        $consumosDia = array_filter($consumosDia, function ($consumoDia) use ($fechaInicio, $fechaFin) {
            return $consumoDia->isFechaBetween($fechaInicio, $fechaFin);
        });

        if (!$consumosDia) {
            return RespuestaController::format("200", "No se encontraron entradas en las fechas indicadas.");
        }

        // foreach ($consumosDia as $consumoDia) {
        //     $consumosDiaJSON[] = $this->consumoDiaJSON($consumoDia);
        // }

        foreach ($consumosDia as $consumoDia) {
            $consumosDiaJSON[] = $this->consumoDiaCompletoJSON($consumoDia, $alimentoRepository, $recetasRepository);
        }

        return RespuestaController::format("200", $consumosDiaJSON);
    }

    /**
     * @Route("/crear/usuario/{id}", name="consumo_dia_usuario", methods={"POST"})
     */
    public function crear(Request $request, ConsumoDiaRepository $consumoDiaRepository, $id, UsuarioRepository $usuarioRepository, AlimentoRepository $alimentoRepository, RecetasRepository $recetasRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        $consumoDia = new ConsumoDia();
        $alimento = new Alimento();

        // Comida acaba siendo el ID de lo que consumira, precedido por 1_ si es alimento o 2_ si es receta
        // if (AlimentoController::buscarNombreSinPeticion($data['comida'])) {
        //     $consumoDia->setComida("1_" . AlimentoController::buscarNombreSinPeticion($data['comida'])->getId());
        // } else if (RecetasController::buscarNombreSinPeticion($data['comida'])) {
        //     $consumoDia->setComida("2_" . AlimentoController::buscarNombreSinPeticion($data['comida'])->getId());
        // }else {
        //     return RespuestaController::format("404", "No se encontró la comida.");
        // }
        $nombre = $data['comida'];

        $cbbdd = new CbbddConsultas();
        $alimentosEncontrados = $cbbdd->consulta("SELECT * FROM recetas WHERE nombre LIKE '%$nombre%'");
        if (!$alimentosEncontrados) {
            $alimentosEncontrados = $cbbdd->consulta("SELECT * FROM alimento WHERE nombre LIKE '%$nombre%'");
            if (!$alimentosEncontrados) {
                return RespuestaController::format("404", "No se encontró la comida.");
            } else {
                $consumoDia->setComida("1_" . $alimentosEncontrados[0]["id"]);
            }
        } else {
            $consumoDia->setComida("2_" . $alimentosEncontrados[0]["id"]);
        }

        // if ($alimentoRepository->findOneBy(["nombre" => $data['comida']])) {
        //     $consumoDia->setComida("1_" . $alimentoRepository->findOneBy(["nombre" => $data['comida']])->getId());
        // } else if ($recetasRepository->findOneBy(["nombre" => $data['comida']])) {
        //     $consumoDia->setComida("2_" . $recetasRepository->findOneBy(["nombre" => $data['comida']])->getId());
        // } else {
        //     return RespuestaController::format("404", "No se encontró la comida.");
        // }
        // $consumoDia->setComida($data['comida']);
        $cantidadFloat = floatval($data['cantidad']);
        var_dump($cantidadFloat);
        $consumoDia->setCantidad($cantidadFloat);
        $consumoDia->setMomento($data['momento']);
        $consumoDia->setFecha(new \DateTime($data['fecha']));
        $consumoDia->setHora(new \DateTime($data['hora']));
        $consumoDia->setIdUsuario($usuarioRepository->find($id));

        $consumoDiaRepository->add($consumoDia, true);

        $consumoDiaJSON = $this->consumoDiaJSON($consumoDia);

        return RespuestaController::format("200", $consumoDiaJSON);
    }

    /**
     * @Route("/editar", name="editar_consumo_dia_usuario", methods={"PUT"})
     */
    public function editar(Request $request, ConsumoDiaRepository $consumoDiaRepository, UsuarioRepository $usuarioRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        $consumoDia = $consumoDiaRepository->findOneBy([
            'comida' => $data['comida'],
            'fecha' => new \DateTime($data['fecha']),
            'hora' => new \DateTime($data['hora']),
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
        $consumoDia->setIdUsuario($usuarioRepository->find($data['idUsuario']));

        $consumoDiaRepository->add($consumoDia, true);

        $consumoDiaJSON = $this->consumoDiaJSON($consumoDia);

        return RespuestaController::format("200", $consumoDiaJSON);
    }

    /**
     * @Route("/eliminar", name="eliminar_consumo_dia_usuario", methods={"DELETE", "POST"})
     */
    public function eliminar(Request $request, ConsumoDiaRepository $consumoDiaRepository)
    {
        $data = json_decode($request->getContent(), true);

        $comidaBuscar = '';

        // Busco el alimento o receta que recibo por el nombre
        if (AlimentoController::buscarNombreSinPeticionID($data['comida'])) {
            $respuesta[] = AlimentoController::buscarNombreSinPeticionID($data['comida']);
            $comidaBuscar = "1_" . $respuesta['repuesta'];
        } else if (RecetasController::buscarNombreSinPeticionID($data['comida'])) {
            $comidaBuscar = "2_" . RecetasController::buscarNombreSinPeticionID($data['comida']);
        } else {
            return RespuestaController::format("404", "No se encontró la comida.");
        }



        // var_dump($id);
        // return RespuestaController::format("200", $comidaBuscar);

        // echo($id);echo "<br>";
        // echo($data['fecha']);echo "<br>";
        // echo($data['hora']);echo "<br>";
        // echo($data['idUsuario']);echo "<br>";
        echo $comidaBuscar;


        $consumoDia = $consumoDiaRepository->findOneBy([
            'comida' => $comidaBuscar,
            'fecha' => new \DateTime($data['fecha']),
            'hora' => new \DateTime($data['hora']),
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

    private function consumoDiaCompletoJSON(ConsumoDia $consumoDia, AlimentoRepository $alimentoRepository, RecetasRepository $recetasRepository)
    {

        $nutrientes = $alimentoRepository->findBy(["nombre" => $consumoDia->getComida()]);

        // if (!$nutrientes){
        //     $nutrientes = $recetasRepository->findBy(["nombre"=> $consumoDia->getComida()]);
        // }

        //Si getComida empieza por 1_ buscar alimento
        //Si getComida empieza por 2_ buscar receta
        $comidaParts = explode("_", $consumoDia->getComida());
        $tipoComida = $comidaParts[0];
        $idComida = $comidaParts[1];

        if ($tipoComida == "1") {
            $alimentoController = new AlimentoController();
            $nutrientes = $alimentoController->buscarAlimento($alimentoRepository, $idComida);
            // $nutrientes = $alimentoRepository->find($idComida);
        } else if ($tipoComida == "2") {
            $recetaController = new RecetasController();
            $nutrientes = $recetaController->buscarAlimento($recetasRepository, $idComida);
            // $nutrientes = $recetasRepository->findOneBy(["id"=> $idComida]);
        }

        if (!$nutrientes) {
            return RespuestaController::format("404", "No se encontraron nutrientes para la comida");
        }

        // var_dump($nutrientes);

        $consumoDiaJSON = [
            "id" => $consumoDia->getId(),
            "comida" => $consumoDia->getComida(),
            "cantidad" => $consumoDia->getCantidad(),
            "momento" => $consumoDia->getMomento(),
            "fecha" => $consumoDia->getFecha(),
            "hora" => $consumoDia->getHora(),
            "idUsuario" => $consumoDia->getIdUsuario(),
            "nutrientes" => $nutrientes
        ];

        return $consumoDiaJSON;
        // return $nutrientes;
    }
}