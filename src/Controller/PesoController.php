<?php

namespace App\Controller;

use App\Entity\Peso;
use App\Form\PesoType;
use App\Repository\PesoRepository;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Util\RespuestaController;

/**
 * @Route("/peso")
 */
class PesoController extends AbstractController
{
   /**
    * @Route("/{$idUsuario}", name="app_peso_index", methods={"GET"})
    */
   public function index($idUsuario, PesoRepository $pesoRepository): Response
   {
      $pesos = $pesoRepository->findByUsuario($idUsuario);

      if (!$pesos) {
         return RespuestaController::format("404", "Este usuario no tiene pesos registrados");
      }

      $pesosJSON = [];

      foreach ($pesos as $peso) {
         $pesosJSON[] = $this->pesoJSON($peso);
      }

      return RespuestaController::format("200", $pesosJSON);
   }

   /**
    * @Route("/buscar", name="app_peso_buscar", methods={"GET"})
    */
   public function buscar(PesoRepository $pesoRepository, Request $request): Response
   {
      $data = json_decode($request->getContent(), true);

      $peso = $pesoRepository->find($data['id']);

      if (!$peso) {
         return RespuestaController::format("404", "No se encontró el peso");
      }

      return RespuestaController::format("200", $this->pesoJSON($peso));
   }

   /**
    * @Route("/crear", name="app_peso_crear", methods={"POST"})
    */
   public function crear(Request $request, PesoRepository $pesoRepository, UsuarioRepository $usuarioRepository): Response
   {
      $data = json_decode($request->getContent(), true);

      if (!$data) {
         return RespuestaController::format("400", "No se han recibido datos");
      }

      $peso = new Peso();
      $peso->setFecha(new \DateTime($data['fecha']));
      $peso->setHora(new \DateTime($data['hora']));
      $peso->setPeso($data['peso']);
      $peso->setIdUsuario($data['idUsuario']);

      $usuario = $usuarioRepository->find($data['idUsuario']);

      if (!$usuario) {
         return RespuestaController::format("400", "Usuario no encontrado");
      }

      if ($usuario->getAltura() != 0) {
         $alturaEnMetros = $usuario->getAltura() / 100;
         $imc = $data['peso'] / ($alturaEnMetros * $alturaEnMetros);
         $peso->setIMC($imc);
      } else {
         return RespuestaController::format("400", "Error durante el cálculo del IMC");
      }

      $pesoRepository->add($peso, true);

      return RespuestaController::format("201", $this->pesoJSON($peso));
   }

   /**
    * @Route("/editar", name="app_peso_editar", methods={"PUT"})
    */
   public function editar($id, Request $request, PesoRepository $pesoRepository): Response
   {
      $data = json_decode($request->getContent(), true);

      if (!$data) {
         return RespuestaController::format("400", "No se han recibido datos");
      }

      //Editar peso por usuario_id, fecha y hora
      $pesoEditar = $pesoRepository->findOneBy([
         'idUsuario' => $data['idUsuario'],
         'fecha' => new \DateTime($data['fecha']),
         'hora' => new \DateTime($data['hora'])
      ]);

      if (!$pesoEditar) {
         return RespuestaController::format("404", "No se encontró registro a editar");
      }

      $pesoEditar->setPeso($data['peso']);

      $pesoRepository->add($pesoEditar, true);

      return RespuestaController::format("200", $this->pesoJSON($pesoEditar));
   }

   /**
    * @Route("/eliminar", name="app_peso_eliminar", methods={"DELETE"})
    */
   public function eliminar(Request $request, PesoRepository $pesoRepository): Response
   {
      $data = json_decode($request->getContent(), true);

      if (!$data) {
         return RespuestaController::format("400", "No se han recibido datos");
      }

      //Encontrar peso por usuario_id, fecha y hora
      $pesoEditar = $pesoRepository->findOneBy([
         'idUsuario' => $data['idUsuario'],
         'fecha' => new \DateTime($data['fecha']),
         'hora' => new \DateTime($data['hora'])
      ]);

      if (!$pesoEditar) {
         return RespuestaController::format("404", "No se encontró registro a eliminar");
      }

      $pesoRepository->remove($pesoEditar, true);

      return RespuestaController::format("200", "Registro eliminado correctamente");
   }

   /**
    * @Route("/usuario/rango/{id}", name="app_peso_rango", methods={"GET"})
    */
   public function getByUsuarioAndFechas($id, PesoRepository $pesoRepository, Request $request): Response
   {
      $data = json_decode($request->getContent(), true);

      $fechaInicio = new \DateTime($data['fechaInicio']);
      $fechaFin = new \DateTime($data['fechaFin']);

      $pesosUsuario = $pesoRepository->findBy(["idUsuario" => $id]);

      $pesosUsuario = array_filter($pesosUsuario, function ($peso) use ($fechaInicio, $fechaFin) {
         return $peso->isFechaBetween($fechaInicio, $fechaFin);
      });

      if (!$pesosUsuario) {
         return RespuestaController::format("404", "No se encontraron pesos en las fechas indicadas.");
      }

      return RespuestaController::format("200", $pesosUsuario);
   }
   private function pesoJSON(Peso $peso)
   {
      $pesoJSON = [];

      $pesoJSON = [
         "id" => $peso->getId(),
         "fecha" => $peso->getFecha(),
         "hora" => $peso->getHora(),
         "peso" => $peso->getPeso(),
         "IMC" => $peso->getIMC(),
         "idUsuario" => $peso->getIdUsuario()->getId()
      ];

      return RespuestaController::format("200", $pesoJSON);
   }
}