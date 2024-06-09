<?php

namespace App\Controller;

use App\Entity\Recetas;
use App\Form\RecetasType;
use App\Repository\RecetasRepository;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Util\RespuestaController;
use App\Util\CbbddConsultas;

/**
 * @Route("/recetas")
 */
class RecetasController extends AbstractController
{
  /**
   * @Route("/usuario/{idUsuario}", name="app_recetas_index", methods={"GET"})
   *
   * Método para obtener las recetas creadas por un usuario
   * @param RecetasRepository $recetasRepository
   * @return Response con las recetas de un usuario en formato JSON
   */
  public function index($idUsuario, RecetasRepository $recetasRepository): Response
  {
    $recetas = $recetasRepository->findBy(["idUsuario" => $idUsuario]);

    if (!$recetas) {
      return RespuestaController::format("404", "No hay recetas registradas por este usuario");
    }

    $recetasJSON = [];

    foreach ($recetas as $receta) {
      $recetasJSON[] = $this->recetasJSON($receta);
    }

    return RespuestaController::format("200", $recetasJSON);
  }

  /**
   * @Route("/unica/{id}", name="app_recetas_buscar", methods={"GET"})
   *
   * Método para obtener una receta en concreto
   * @param RecetasRepository $recetasRepository
   * @return Response con la receta en formato JSON
   */
  public function buscar($id, RecetasRepository $recetasRepository): Response
  {
    $recetas = $recetasRepository->find($id);

    if (!$recetas) {
      return RespuestaController::format("404", "No se ha encontrado la receta");
    }

    return RespuestaController::format("200", $this->recetasJSON($recetas));
  }

  /**
   * @Route("/crear", name="app_recetas_crear", methods={"POST"})
   *
   * Método para crear una receta
   * @param Request $request
   * @param RecetasRepository $recetasRepository
   * @param UsuarioRepository $usuarioRepository
   * @return Response con la receta creada en formato JSON
   */
  public function crear(Request $request, RecetasRepository $recetasRepository, UsuarioRepository $usuarioRepository): Response
  {
    $data = json_decode($request->getContent(), true);

    if (!$data) {
      return RespuestaController::format("400", "No se han recibido datos");
    }

    // Compruebo que el usuario no tenga una receta con el mismo nombre
    $recetasExistente = $recetasRepository->findBy(["idUsuario" => $data['idUsuario']]);

    foreach ($recetasExistente as $receta) {
      if ($receta->getNombre() === $data['nombre']) {
        return RespuestaController::format("400", "Ya existe una receta con el mismo nombre");
      }
    }

    $receta = new Recetas();
    $receta->setNombre($data['nombre']);
    $receta->setDescripcion($data['descripcion']);
    $receta->setInstrucciones($data['instrucciones']);
    $receta->setCantidadFinal($data['cantidadFinal']);
    $receta->setProteinas($data['proteinas']);
    $receta->setGrasas($data['grasas']);
    $receta->setCarbohidratos($data['carbohidratos']);
    $receta->setAzucares($data['azucares']);
    $receta->setVitaminas($data['vitaminas']);
    $receta->setCalorias($data['calorias']);
    $receta->setImagen($data['imagen']);
    $receta->setIdUsuario($usuarioRepository->find($data['idUsuario']));

    $recetasRepository->add($receta, true);

    return RespuestaController::format("200", $this->recetasJSON($receta));
  }

  /**
   * @Route("/editar", name="app_recetas_editar", methods={"PUT"})
   *
   * Método para editar una receta
   * @param Request $request
   * @param RecetasRepository $recetasRepository
   * @return Response con la receta editada en formato JSON
   */
  public function editar(Request $request, RecetasRepository $recetasRepository): Response
  {
    $data = json_decode($request->getContent(), true);

    if (!$data) {
      return RespuestaController::format("400", "No se han recibido datos");
    }

    $receta = $recetasRepository->find($data['id']);

    if (!$receta) {
      return RespuestaController::format("404", "No se ha encontrado la receta");
    }

    $receta->setNombre($data['nombre']);
    $receta->setDescripcion($data['descripcion']);
    $receta->setInstrucciones($data['instrucciones']);
    $receta->setCantidadFinal($data['cantidadFinal']);
    $receta->setProteinas($data['proteinas']);
    $receta->setGrasas($data['grasas']);
    $receta->setCarbohidratos($data['carbohidratos']);
    $receta->setAzucares($data['azucares']);
    $receta->setVitaminas($data['vitaminas']);
    $receta->setCalorias($data['calorias']);
    $receta->setImagen($data['imagen']);

    $recetasRepository->add($receta, true);

    return RespuestaController::format("200", $this->recetasJSON($receta));
  }

  /**
   * @Route("/eliminar", name="app_recetas_eliminar", methods={"DELETE"})
   *
   * Método para eliminar una receta
   * @param Request $request
   * @param RecetasRepository $recetasRepository
   * @return Response con el mensaje de éxito o error
   */
  public function eliminar(Request $request, RecetasRepository $recetasRepository): Response
  {
    $data = json_decode($request->getContent(), true);

    if (!$data) {
      return RespuestaController::format("400", "No se han recibido datos");
    }

    $receta = $recetasRepository->find($data['id']);

    if (!$receta) {
      return RespuestaController::format("404", "No se ha encontrado la receta");
    }

    $recetasRepository->remove($receta, true);

    return RespuestaController::format("200", "Receta eliminada");
  }

  /**
   * @Route("/buscarnombre", name="app_alimento_buscarnombre", methods={"POST"})
   *
   * Método para buscar un alimento por su nombre
   * @param Request $request
   * @return Response con el alimento encontrado en formato JSON
   */
  public function buscarnombre(Request $request)
  {
    $nombreBuscar = json_decode($request->getContent(), true)["nombre"];

    $cbbdd = new CbbddConsultas();
    $alimentosEncontrados = $cbbdd->consulta("SELECT * FROM recetas WHERE nombre LIKE '%$nombreBuscar%'");
    if (!$alimentosEncontrados) {
      // Aquí el codigo de error deberia ser diferente
      return RespuestaController::format("200", "No se ha encontrado el alimento");
    } else {
      return RespuestaController::format("200", $alimentosEncontrados);
    }
  }

  /**
   * Método para buscar un alimento por su nombre sin necesidad de petición
   * @param Request $request
   * @return Response con el alimento encontrado en formato JSON
   */
  public static function buscarNombreSinPeticion($nombre)
  {
    $cbbdd = new CbbddConsultas();
    $alimentosEncontrados = $cbbdd->consulta("SELECT * FROM recetas WHERE nombre LIKE '%$nombre%'");
    if (!$alimentosEncontrados) {
      // Aquí el codigo de error deberia ser diferente
      return RespuestaController::format("200", "No se ha encontrado el alimento");
    } else {
      return RespuestaController::format("200", $alimentosEncontrados);
    }
  }

  /**
   * Método para buscar un alimento por su nombre sin necesidad de petición obteniendo el ID
   * @param Request $request
   * @return Response con el alimento encontrado en formato JSON
   */
  public static function buscarNombreSinPeticionID($nombre)
  {
    $cbbdd = new CbbddConsultas();
    $recetaEncontrada = $cbbdd->consulta("SELECT ID FROM recetas WHERE nombre LIKE '%$nombre%'");
    if (!$recetaEncontrada) {
      // Aquí el codigo de error deberia ser diferente
      return RespuestaController::format("200", "No se ha encontrado el alimento");
    } else {
      var_dump($recetaEncontrada);
      return RespuestaController::format("200", $recetaEncontrada);
    }
  }


  /**
   * Método para convertir una receta en un array JSON
   * @param Recetas $receta
   * @return array con la receta en formato JSON
   */
  private function recetasJSON(Recetas $receta)
  {
    $recetasJSON = [];

    $recetasJSON = [
      "nombre" => $receta->getNombre(),
      "descripcion" => $receta->getDescripcion(),
      "instrucciones" => $receta->getInstrucciones(),
      "cantidadFinal" => $receta->getCantidadFinal(),
      "proteinas" => $receta->getProteinas(),
      "grasas" => $receta->getGrasas(),
      "carbohidratos" => $receta->getCarbohidratos(),
      "azucares" => $receta->getAzucares(),
      "vitaminas" => $receta->getVitaminas(),
      "calorias" => $receta->getCalorias(),
      "imagen" => $receta->getImagen(),
      "id" => $receta->getId(),
    ];

    return $recetasJSON;
  }

  /**
   * Método para buscar un alimento por su ID
   * @param RecetasRepository $recetasRepository
   * @param $id
   * @return Response con el alimento encontrado en formato JSON
   */
  public function buscarReceta(RecetasRepository $recetasRepository, $id): Response
  {
      $receta = $recetasRepository->find($id);

      return new Response($this->recetasJSON($receta));
  }
}