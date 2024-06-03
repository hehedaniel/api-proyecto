<?php

namespace App\Controller;

use App\Entity\Alimento;
use App\Repository\AlimentoRepository;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Util\RespuestaController;


/**
 * @Route("/alimento")
 */
class AlimentoController extends AbstractController
{
    /**
     * @Route("/index", name="app_usuario_index", methods={"GET"})
     */

    public function index(AlimentoRepository $alimentoRepository): Response
    {
        $alimentos = $alimentoRepository->findAll();

        if (!$alimentos) {
            return RespuestaController::format("404", "No hay alimentos registrados");
        }

        $alimentosJSON = [];

        foreach ($alimentos as $alimento) {
            $alimentosJSON[] = $this->alimentosJSON($alimento);
        }

        return RespuestaController::format("200", $alimentosJSON);
    }

    /**
     * @Route("/index/{id}", name="app_alimento_buscar", methods={"GET"})
     */

    public function buscar($id, AlimentoRepository $alimentoRepository): Response
    {
        $alimento = $alimentoRepository->find($id);

        if (!$alimento) {
            //Si no encuentra por ID busca por nombre
            if ($alimentoRepository->findOneBy(["nombre" => $id])){
                $alimento = $alimentoRepository->findOneBy(["nombre" => $id]);
            } else {
                return RespuestaController::format("404", "No se ha encontrado el alimento");
            }
        }

        $alimentoJSON = $this->alimentosJSON($alimento);

        return RespuestaController::format("200", $alimentoJSON);
    }

    /**
     * @Route("/crear", name="app_alimento_crear", methods={"POST"})
     */
    public function crear(Request $request, AlimentoRepository $alimentoRepository, UsuarioRepository $usuarioRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return RespuestaController::format("400", "No se han recibido datos");
        }

        if($alimentoRepository->findOneBy(["nombre" => $data["nombre"]])){
            return RespuestaController::format("400", "El alimento ya existe");
        }

        $alimento = new Alimento();
        $alimento->setNombre($data["nombre"]);
        $alimento->setDescripcion($data["descripcion"]);
        $alimento->setMarca($data["marca"]);
        $alimento->setCantidad($data["cantidad"]);
        $alimento->setProteinas($data["proteinas"]);
        $alimento->setGrasas($data["grasas"]);
        $alimento->setCarbohidratos($data["carbohidratos"]);
        $alimento->setAzucares($data["azucares"]);
        $alimento->setVitaminas($data["vitaminas"]);
        $alimento->setMinerales($data["minerales"]);
        $alimento->setImagen($data["imagen"]);
        //Cambio de idUsuario a usuario por problema recibido en las pruebas
        $usuario = $usuarioRepository->find($data["idUsuario"]);
        $alimento->setIdUsuario($usuario);

        $alimentoRepository->add($alimento, true);

        $alimentoJSON = $this->alimentosJSON($alimento);

        return RespuestaController::format("200", $alimentoJSON);
    }

    /**
     * @Route("/editar/{id}", name="app_alimento_editar", methods={"PUT"})
     */

    public function editar($id, Request $request, AlimentoRepository $alimentoRepository, UsuarioRepository $usuarioRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return RespuestaController::format("400", "No se han recibido datos");
        }

        $alimento = $alimentoRepository->find($id);

        if (!$alimento) {
            return RespuestaController::format("404", "No se ha encontrado el alimento");
        }

        $alimento->setNombre($data["nombre"]);
        $alimento->setDescripcion($data["descripcion"]);
        $alimento->setMarca($data["marca"]);
        $alimento->setCantidad($data["cantidad"]);
        $alimento->setProteinas($data["proteinas"]);
        $alimento->setGrasas($data["grasas"]);
        $alimento->setCarbohidratos($data["carbohidratos"]);
        $alimento->setAzucares($data["azucares"]);
        $alimento->setVitaminas($data["vitaminas"]);
        $alimento->setMinerales($data["minerales"]);
        $alimento->setImagen($data["imagen"]);
        //Cambio de idUsuario a usuario por problema recibido en las pruebas
        $usuario = $usuarioRepository->find($data["idUsuario"]);
        $alimento->setIdUsuario($usuario);

        $alimentoRepository->add($alimento, true);

        $alimentoJSON = $this->alimentosJSON($alimento);

        return RespuestaController::format("200", $alimentoJSON);
    }

    /**
     * @Route("/eliminar", name="app_alimento_eliminar", methods={"DELETE"})
     */
    public function eliminar(Request $request, AlimentoRepository $alimentoRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return RespuestaController::format("400", "No se han recibido datos");
        }

        $alimento = $alimentoRepository->find($data["id"]);

        if (!$alimento) {
            return RespuestaController::format("404", "No se ha encontrado el alimento");
        }

        $alimentoRepository->remove($alimento, true);

        return RespuestaController::format("200", "Alimento eliminado");
    }


    private function alimentosJSON(Alimento $alimento)
    {
        $alimentosJSON = [
            "id" => $alimento->getId(),
            "nombre" => $alimento->getNombre(),
            "descripcion" => $alimento->getDescripcion(),
            "marca" => $alimento->getMarca(),
            "cantidad" => $alimento->getCantidad(),
            "proteinas" => $alimento->getProteinas(),
            "grasas" => $alimento->getGrasas(),
            "carbohidratos" => $alimento->getCarbohidratos(),
            "azucares" => $alimento->getAzucares(),
            "vitaminas" => $alimento->getVitaminas(),
            "minerales" => $alimento->getMinerales(),
            "imagen" => $alimento->getImagen(),
            "idUsuario" => $alimento->getIdUsuario(),
        ];

        return $alimentosJSON;
    }


}