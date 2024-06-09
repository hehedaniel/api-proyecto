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
    * @Route("/usuario/{id}", name="app_peso_index", methods={"GET"})
    */
   public function index($id, PesoRepository $pesoRepository, UsuarioRepository $usuarioRepository): Response
   {
      $usuario = $usuarioRepository->find($id);
      $pesos = $pesoRepository->findBy(["idUsuario" => $usuario->getId()]);

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
    * @Route("/buscar/{id}", name="app_peso_buscar", methods={"GET"})
    */
   public function buscar(PesoRepository $pesoRepository, Request $request, $id): Response
   {
      $peso = $pesoRepository->find($id);

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

      $usuario = $usuarioRepository->find($data['idUsuario']);

      if ($pesoRepository->findOneBy([
         'idUsuario' => $usuario->getId(),
         'fecha' => new \DateTime($data['fecha']),
         'hora' => new \DateTime($data['hora'])
      ])) {
         return RespuestaController::format("400", "Ya existe un peso registrado para esta fecha, hora y usuario");
      }

      $peso = new Peso();
      $peso->setFecha(new \DateTime($data['fecha']));
      $peso->setHora(new \DateTime($data['hora']));
      $peso->setPeso($data['peso']);
      $peso->setIdUsuario($usuario);

      if (!$usuario) {
         return RespuestaController::format("400", "Usuario no encontrado");
      }

      if ($usuario->getAltura() != 0) {
         $alturaEnMetros = $usuario->getAltura() / 100;
         $imc = round($data['peso'] / ($alturaEnMetros * $alturaEnMetros),1);
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
   public function editar(Request $request, PesoRepository $pesoRepository): Response
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
    * @Route("/eliminar/{idPeso}", name="app_peso_eliminar", methods={"DELETE"})
    */
   public function eliminar($idPeso, PesoRepository $pesoRepository): Response
   {
      // $data = json_decode($request->getContent(), true);

      // if (!$data) {
      //    return RespuestaController::format("400", "No se han recibido datos");
      // }

      // //Encontrar peso por usuario_id, fecha y hora
      // $pesoEditar = $pesoRepository->findOneBy([
      //    'idUsuario' => $data['idUsuario'],
      //    'fecha' => new \DateTime($data['fecha']),
      //    'hora' => new \DateTime($data['hora'])
      // ]);

      if (!$idPeso) {
         return RespuestaController::format("404", "No se recibio el identificado");
      }

      $pesoEliminar = $pesoRepository->find($idPeso);

      $pesoRepository->remove($pesoEliminar, true);

      return RespuestaController::format("200", "Registro eliminado correctamente");
   }

   /**
    * @Route("/rangofechas", name="app_peso_rango", methods={"POST"})
    */
   public function getByUsuarioAndFechas(PesoRepository $pesoRepository, Request $request): Response
   {
      $data = json_decode($request->getContent(), true);

      $fechaInicio = new \DateTime($data['fechaInicio']);
      $fechaFin = new \DateTime($data['fechaFin']);

      $pesosUsuario = $pesoRepository->findBy(["idUsuario" => $data['idUsuario']]);

      $pesosUsuario = array_filter($pesosUsuario, function ($peso) use ($fechaInicio, $fechaFin) {
         return $peso->isFechaBetween($fechaInicio, $fechaFin);
      });

      if (!$pesosUsuario) {
         return RespuestaController::format("200", "No se encontraron pesos en las fechas indicadas.");
      }

      $pesosUsuarioJSON = [];

      foreach ($pesosUsuario as $peso) {
         $pesosUsuarioJSON[] = $this->pesoJSON($peso);
      }

      return RespuestaController::format("200", $pesosUsuarioJSON);
   }

   /**
    * @Route("/encontrar", name="app_peso_encontrar", methods={"POST"})
    */
   public function encontrar(PesoRepository $pesoRepository, Request $request): Response
   {
      $data = json_decode($request->getContent(), true);

      $peso = $pesoRepository->findOneBy([
         'idUsuario' => $data['idUsuario'],
         'fecha' => new \DateTime($data['fecha']),
         'hora' => new \DateTime($data['hora'])
      ]);

      if (!$peso) {
         return RespuestaController::format("404", "No se encontró el peso");
      }

      return RespuestaController::format("200", $this->pesoJSON($peso));
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

      return $pesoJSON;
   }
}