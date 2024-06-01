<?php

namespace App\Controller;

use App\Entity\Recetas;
use App\Form\RecetasType;
use App\Repository\RecetasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Util\RespuestaController;

/**
 * @Route("/recetas")
 */
class RecetasController extends AbstractController
{
  /**
   * @Route("/", name="app_recetas_index", methods={"GET"})
   */
  public function index(RecetasRepository $recetasRepository): Response
  {
    $recetas = $recetasRepository->findAll();

    if (!$recetas) {
      return RespuestaController::format("404", "No hay recetas registradas");
    }

    $recetasJSON = [];

    foreach ($recetas as $receta) {
      $recetasJSON[] = $this->recetasJSON($receta);
    }

    return RespuestaController::format("200", $recetasJSON);
  }

  /**
   * @Route("/{id}", name="app_recetas_buscar", methods={"GET"})
   */
  public function buscar($id, RecetasRepository $recetasRepository): Response
  {
    $recetas = $recetasRepository->find($id);

    if (!$recetas) {
      if ($recetasRepository->findOneBy(["nombre" => $id])) {
        $recetas = $recetasRepository->findOneBy(["nombre" => $id]);
      } else {
        return RespuestaController::format("404", "No se ha encontrado la receta");
      }
    }

    return RespuestaController::format("200", $this->recetasJSON($recetas));
  }

  /**
   * @Route("/crear", name="app_recetas_crear", methods={"POST"})
   */
  public function crear(Request $request, RecetasRepository $recetasRepository): Response
  {
    $data = json_decode($request->getContent(), true);

    if (!$data) {
      return RespuestaController::format("400", "No se han recibido datos");
    }

    $recetasExistente = $recetasRepository->findBy(["id_usuario_id" => $data['id_usuario_id']]);

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
    $receta->setMinerales($data['minerales']);
    $receta->setImagen($data['imagen']);
    $receta->setIdUsuario($data['id_usuario_id']);

    $recetasRepository->add($receta, true);

    return RespuestaController::format("200", $this->recetasJSON($receta));
  }

  /**
   * @Route("/editar/{id}", name="app_recetas_editar", methods={"PUT"})
   */
  public function editar($id, Request $request, RecetasRepository $recetasRepository): Response
  {
    $data = json_decode($request->getContent(), true);

    if (!$data) {
      return RespuestaController::format("400", "No se han recibido datos");
    }

    $receta = $recetasRepository->find($id);

    if (!$receta) {
      return RespuestaController::format("404", "No se ha encontrado la receta");
    }

    //Aqui podria comprobar que pertenece al usuario

    $receta->setNombre($data['nombre']);
    $receta->setDescripcion($data['descripcion']);
    $receta->setInstrucciones($data['instrucciones']);
    $receta->setCantidadFinal($data['cantidadFinal']);
    $receta->setProteinas($data['proteinas']);
    $receta->setGrasas($data['grasas']);
    $receta->setCarbohidratos($data['carbohidratos']);
    $receta->setAzucares($data['azucares']);
    $receta->setVitaminas($data['vitaminas']);
    $receta->setMinerales($data['minerales']);
    $receta->setImagen($data['imagen']);

    $recetasRepository->add($receta, true);

    return RespuestaController::format("200", $this->recetasJSON($receta));
  }

  /**
   * @Route("/eliminar", name="app_recetas_eliminar", methods={"DELETE"})
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


  private function recetasJSON(Recetas $receta){
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
        "minerales" => $receta->getMinerales(),
        "imagen"=> $receta->getImagen(),
        "id" => $receta->getId(),
      ];

    return RespuestaController::format("200", $recetasJSON);
  }
}