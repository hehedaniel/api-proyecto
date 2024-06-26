<?php

namespace App\Controller;

use App\Entity\UsuarioRealizaEjercicio;
use App\Repository\EjercicioRepository;
use App\Repository\UsuarioRealizaEjercicioRepository;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Util\RespuestaController;

/**TODO
 * en un rango de fechas para un usuario
 */

/**
 * @Route("/usuariorealizaejercicio")
 */
class UsuarioRealizaEjercicioController extends AbstractController
{
   /**
    * @Route("/usuario/{id}", name="usuario_realiza_ejercicio_usuario", methods={"GET"})
    */
   public function getByUsuario($id, UsuarioRealizaEjercicioRepository $usuarioRealizaEjercicioRepository, EjercicioRepository $ejercicioRepository): Response
   {

      $fechaActual = new \DateTime();
      $fechaActualFormatted = $fechaActual->format('Y-m-d');
      $fecha = new \DateTime($fechaActualFormatted);

      $usuarioRealizaEjercicio = $usuarioRealizaEjercicioRepository->findBy([
         'idUsuario' => $id,
         'fecha' => $fecha
      ]);

      $ejerciciosUsuario = [];

      foreach ($usuarioRealizaEjercicio as $ejercicioUsuario) {
         $jercicioNombre = $ejercicioRepository->find($ejercicioUsuario->getIdEjercicio())->getNombre();
         $ejerciciosUsuario[] = $this->usuarioRealizaEjercicioInfoEjercicioJSON($ejercicioUsuario, $jercicioNombre);
      }

      return RespuestaController::format("200", $ejerciciosUsuario);
   }

   /**
    * @Route("/realiza", name="usuario_realiza_ejercicio_crear", methods={"POST"})
    */

   public function crear(Request $request, UsuarioRealizaEjercicioRepository $usuarioRealizaEjercicioRepository, EjercicioRepository $ejercicioRepository, UsuarioRepository $usuarioRepository): Response
   {
      $data = json_decode($request->getContent(), true);

      $usuarioRealizaEjercicio = new UsuarioRealizaEjercicio();
      $usuarioRealizaEjercicio->setFecha(new \DateTime($data['fecha']));
      $usuarioRealizaEjercicio->setHora(new \DateTime($data['hora']));
      $usuarioRealizaEjercicio->setCalorias($data['calorias']);
      // Aqui no cambio las calorias ya que quiero probar a intentar sacarlas desde el fronted
      $usuarioRealizaEjercicio->setTiempo($data['tiempo']);

      $usuarioRealizaEjercicio->setIdEjercicio($ejercicioRepository->find($data['idEjercicio']));
      $usuarioRealizaEjercicio->setIdUsuario($usuarioRepository->find($data['idUsuario']));

      $usuarioRealizaEjercicioRepository->add($usuarioRealizaEjercicio, true);

      return RespuestaController::format("200", $this->usuarioRealizaEjercicioJSON($usuarioRealizaEjercicio));
   }

   /**
    * @Route("/eliminar", name="usuario_realiza_ejercicio_eliminar", methods={"DELETE"})
    */
   public function eliminar(UsuarioRealizaEjercicioRepository $usuarioRealizaEjercicioRepository, Request $request): Response
   {
      $data = json_decode($request->getContent(), true);

      $usuarioRealizaEjercicio = $usuarioRealizaEjercicioRepository->findOneBy(
         [
            "idUsuario" => $data['idUsuario'],
            "fecha" => new \DateTime($data['fecha']),
            "hora" => new \DateTime($data['hora']),
            "idEjercicio" => $data['idEjercicio']
         ]
      );

      if (!$usuarioRealizaEjercicio) {
         return RespuestaController::format("404", "No existe el ejercicio a eliminar");
      }

      $usuarioRealizaEjercicioRepository->remove($usuarioRealizaEjercicio, true);

      return RespuestaController::format("200", "Ejercicio eliminado correctamente");
   }

   /**
    * @Route("/editar", name="usuario_realiza_ejercicio_editar", methods={"PUT"})
    */

   public function editar(Request $request, UsuarioRealizaEjercicioRepository $usuarioRealizaEjercicioRepository): Response
   {
      $data = json_decode($request->getContent(), true);

      $usuarioRealizaEjercicio = $usuarioRealizaEjercicioRepository->findOneBy(
         [
            "idUsuario" => $data['idUsuario'],
            "fecha" => new \DateTime($data['fecha']),
            "hora" => new \DateTime($data['hora']),
            "idEjercicio" => $data['idEjercicio']
         ]
      );

      if (!$usuarioRealizaEjercicio) {
         return RespuestaController::format("404", "No existe el ejercicio a editar");
      }

      // $usuarioRealizaEjercicio->setHora(new \DateTime($data['hora'])); // No se puede modificar la hora
      $usuarioRealizaEjercicio->setCalorias($data['calorias']);
      $usuarioRealizaEjercicio->setTiempo($data['tiempo']);

      $usuarioRealizaEjercicioRepository->add($usuarioRealizaEjercicio, true);

      return RespuestaController::format("200", $this->usuarioRealizaEjercicioJSON($usuarioRealizaEjercicio));
   }


   public function usuarioRealizaEjercicioJSON(UsuarioRealizaEjercicio $usuarioRealizaEjercicio)
   {
      $usuarioRealizaEjercicioJSON = [
         "id" => $usuarioRealizaEjercicio->getId(),
         "fecha" => $usuarioRealizaEjercicio->getFecha(),
         "hora" => $usuarioRealizaEjercicio->getHora(),
         "calorias" => $usuarioRealizaEjercicio->getCalorias(),
         "tiempo" => $usuarioRealizaEjercicio->getTiempo(),
         "idEjercicio" => $usuarioRealizaEjercicio->getIdEjercicio(),
         "idUsuario" => $usuarioRealizaEjercicio->getIdUsuario(),
      ];
      // foreach ($usuarioRealizaEjercicio as $ejerciciosUsuario) {
      //    $usuarioRealizaEjercicioJSON[] = [
      //       "id" => $ejerciciosUsuario->getId(),
      //       "fecha" => $ejerciciosUsuario->getFecha(),
      //       "hora" => $ejerciciosUsuario->getHora(),
      //       "calorias" => $ejerciciosUsuario->getCalorias(),
      //       "tiempo" => $ejerciciosUsuario->getTiempo(),
      //       "idEjercicio" => $ejerciciosUsuario->getIdEjercicio(),
      //       "idUsuario" => $ejerciciosUsuario->getIdUsuario(),
      //    ];
      // }

      return $usuarioRealizaEjercicioJSON;
   }

   public function usuarioRealizaEjercicioInfoEjercicioJSON(UsuarioRealizaEjercicio $usuarioRealizaEjercicio, String $nombreEjercio)
   {
      $usuarioRealizaEjercicioJSON = [
         "id" => $usuarioRealizaEjercicio->getId(),
         "fecha" => $usuarioRealizaEjercicio->getFecha(),
         "hora" => $usuarioRealizaEjercicio->getHora(),
         "calorias" => $usuarioRealizaEjercicio->getCalorias(),
         "tiempo" => $usuarioRealizaEjercicio->getTiempo(),
         "idEjercicio" => $usuarioRealizaEjercicio->getIdEjercicio(),
         "idUsuario" => $usuarioRealizaEjercicio->getIdUsuario(),
         "ejNombre" => $nombreEjercio
      ];
      // foreach ($usuarioRealizaEjercicio as $ejerciciosUsuario) {
      //    $usuarioRealizaEjercicioJSON[] = [
      //       "id" => $ejerciciosUsuario->getId(),
      //       "fecha" => $ejerciciosUsuario->getFecha(),
      //       "hora" => $ejerciciosUsuario->getHora(),
      //       "calorias" => $ejerciciosUsuario->getCalorias(),
      //       "tiempo" => $ejerciciosUsuario->getTiempo(),
      //       "idEjercicio" => $ejerciciosUsuario->getIdEjercicio(),
      //       "idUsuario" => $ejerciciosUsuario->getIdUsuario(),
      //    ];
      // }

      return $usuarioRealizaEjercicioJSON;
   }
}