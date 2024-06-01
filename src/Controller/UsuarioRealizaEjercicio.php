<?php

namespace App\Controller;

use App\Entity\UsuarioRealizaEjercicio;
use App\Repository\UsuarioRealizaEjercicioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Util\RespuestaController;

/**TODO
 * en un rango de fechas para un usuario
 */

/**
 * @Route("/usuario_realiza_ejercicio")
 */
class UsuarioRealizaEjercicioController extends AbstractController
{
   /**
    * @Route("/usuario/{id}", name="usuario_realiza_ejercicio_usuario", methods={"GET"})
    */
   public function getByUsuario(UsuarioRealizaEjercicioRepository $usuarioRealizaEjercicioRepository, $id): Response
   {
      $usuarioRealizaEjercicio = $usuarioRealizaEjercicioRepository->findByUsuario($id);

      return RespuestaController::format("200", $this->usuarioRealizaEjercicioJSON($usuarioRealizaEjercicio));
   }

   /**
    * @Route("/usuario/realiza", name="usuario_realiza_ejercicio_crear", methods={"POST"})
    */

   public function crear(Request $request, UsuarioRealizaEjercicioRepository $usuarioRealizaEjercicioRepository): Response
   {
      $data = json_decode($request->getContent(), true);

      $usuarioRealizaEjercicio = new UsuarioRealizaEjercicio();
      $usuarioRealizaEjercicio->setFecha(new \DateTime($data['fecha']));
      $usuarioRealizaEjercicio->setHora(new \DateTime($data['hora']));
      $usuarioRealizaEjercicio->setCalorias($data['calorias']);
      // Aqui no cambio las calorias ya que quiero probar a intentar sacarlas desde el fronted
      $usuarioRealizaEjercicio->setTiempo($data['tiempo']);
      $usuarioRealizaEjercicio->setIdEjercicio($data['idEjercicio']);
      $usuarioRealizaEjercicio->setIdUsuario($data['idUsuario']);

      $usuarioRealizaEjercicioRepository->add($usuarioRealizaEjercicio);

      return RespuestaController::format("200", $this->usuarioRealizaEjercicioJSON($usuarioRealizaEjercicio));
   }

   /**
    * @Route("/usuario/eliminar", name="usuario_realiza_ejercicio_eliminar", methods={"DELETE"})
    */
   public function eliminar(UsuarioRealizaEjercicioRepository $usuarioRealizaEjercicioRepository, Request $request): Response
   {
      $data = json_decode($request->getContent(), true);

      $usuarioRealizaEjercicio = $usuarioRealizaEjercicioRepository->findOneBy(
         [
            "idUsuario" => $data['idUsuario'],
            "fecha" => $data['fecha'],
            "hora" => $data['hora'],
            "idEjercicio" => $data['idEjercicio']
         ]
      );

      if (!$usuarioRealizaEjercicio) {
         return RespuestaController::format("404", "No existe el ejercicio a eliminar");
      }

      $usuarioRealizaEjercicioRepository->remove($usuarioRealizaEjercicio);

      return RespuestaController::format("200", "Ejercicio eliminado correctamente");
   }

   /**
    * @Route("/usuario/editar", name="usuario_realiza_ejercicio_editar", methods={"PUT"})
    */

   public function editar(Request $request, UsuarioRealizaEjercicioRepository $usuarioRealizaEjercicioRepository): Response
   {
      $data = json_decode($request->getContent(), true);

      $usuarioRealizaEjercicio = $usuarioRealizaEjercicioRepository->findOneBy(
         [
            "idUsuario" => $data['idUsuario'],
            "fecha" => $data['fecha'],
            "hora" => $data['hora'],
            "idEjercicio" => $data['idEjercicio']
         ]
      );

      if (!$usuarioRealizaEjercicio) {
         return RespuestaController::format("404", "No existe el ejercicio a editar");
      }

      $usuarioRealizaEjercicio->setHora(new \DateTime($data['hora']));
      $usuarioRealizaEjercicio->setCalorias($data['calorias']);
      $usuarioRealizaEjercicio->setTiempo($data['tiempo']);

      $usuarioRealizaEjercicioRepository->add($usuarioRealizaEjercicio);

      return RespuestaController::format("200", $this->usuarioRealizaEjercicioJSON($usuarioRealizaEjercicio));
   }


   public function usuarioRealizaEjercicioJSON(UsuarioRealizaEjercicio $usuarioRealizaEjercicio)
   {
      $usuarioRealizaEjercicioJSON = [];
      foreach ($usuarioRealizaEjercicio as $ejerciciosUsuario) {
         $usuarioRealizaEjercicioJSON[] = [
            "id" => $ejerciciosUsuario->getId(),
            "fecha" => $ejerciciosUsuario->getFecha(),
            "hora" => $ejerciciosUsuario->getHora(),
            "calorias" => $ejerciciosUsuario->getCalorias(),
            "tiempo" => $ejerciciosUsuario->getTiempo(),
            "idEjercicio" => $ejerciciosUsuario->getIdEjercicio(),
            "idUsuario" => $ejerciciosUsuario->getIdUsuario(),
         ];
      }
      return $usuarioRealizaEjercicioJSON;
   }
}