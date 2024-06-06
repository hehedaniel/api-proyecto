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
use App\Util\CbbddConsultas;

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
            if ($alimentoRepository->findOneBy(["nombre" => $id])) {
                $alimento = $alimentoRepository->findOneBy(["nombre" => $id]);
            } else {
                return RespuestaController::format("404", "No se ha encontrado el alimento");
            }
        }

        $alimentoJSON = $this->alimentosJSON($alimento);

        return RespuestaController::format("200", $alimentoJSON);
    }

    /**
     * @Route("/buscarnombre", name="app_alimento_buscarnombre", methods={"POST"})
     */
    public function buscarnombre(Request $request, AlimentoRepository $alimentoRepository)
    {
        $nombreBuscar = json_decode($request->getContent(), true)["nombre"];

        $cbbdd = new CbbddConsultas();
        $alimentosEncontrados = $cbbdd->consulta("SELECT * FROM alimento WHERE nombre LIKE '%$nombreBuscar%'");
        if (!$alimentosEncontrados) {
            // Aquí el codigo de error deberia ser diferente
            return RespuestaController::format("200", "No se ha encontrado el alimento");
        } else {
            return RespuestaController::format("200", $alimentosEncontrados);
        }
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

        if ($alimentoRepository->findOneBy(["nombre" => $data["nombre"]])) {
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
        $alimento->setCalorias($data["Calorias"]);
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
        $alimento->setCalorias($data["calorias"]);
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

    public function buscarAlimento(AlimentoRepository $alimentoRepository, $id)
    {
        $alimento = $alimentoRepository->find($id);

        return $this->alimentosJSON($alimento);
    }

    public function alimentosJSON(Alimento $alimento)
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
            "calorias" => $alimento->getCalorias(),
            "imagen" => $alimento->getImagen(),
            "idUsuario" => $alimento->getIdUsuario(),
        ];


        return $alimentosJSON;
    }

    public function buscarNombreSinPeticion($nombre)
    {

        $cbbdd = new CbbddConsultas();
        $recetaEncontrada = $cbbdd->consulta("SELECT * FROM recetas WHERE nombre LIKE '%$nombre%'");
        if (!$recetaEncontrada) {
            // Aquí el codigo de error deberia ser diferente
            return RespuestaController::format("200", "No se ha encontrado el alimento");
        } else {
            return RespuestaController::format("200", $recetaEncontrada);
        }
    }


    public function ejecutarSentenciaSQL($sql)
    {
        // Aquí puedes agregar la lógica para ejecutar la sentencia SQL
        // Puedes utilizar la conexión a la base de datos o un ORM como Doctrine

        // Ejemplo de ejecución de sentencia SQL utilizando Doctrine:
        $entityManager = $this->getDoctrine()->getManager();
        $connection = $entityManager->getConnection();
        $statement = $connection->prepare($sql);
        $statement->execute();

        // Puedes retornar el resultado de la sentencia SQL si es necesario
        // return $statement->fetchAll();

        // O realizar cualquier otra acción necesaria con el resultado de la sentencia SQL
    }

}