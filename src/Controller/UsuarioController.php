<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Util\RespuestaController;


/**
 * @Route("/usuario")
 */
class UsuarioController extends AbstractController
{
   /**
    * @Route("/", name="app_usuario_index", methods={"GET"})
    *
    * Metodo para obtener todos los usuarios registrados
    * @param UsuarioRepository $usuarioRepository
    * @return Response con los usuarios en formato JSON
    */
   public function index(UsuarioRepository $usuarioRepository): Response
   {
      $usuarios = $usuarioRepository->findAll();

      if (!$usuarios) {
         return RespuestaController::format("404", "No hay usuarios registrados");
      }

      $usuariosJSON = [];

      foreach ($usuarios as $usuario) {
         $usuariosJSON[] = [
            "id" => $usuario->getId(),
            "nombre" => $usuario->getNombre(),
            "apellidos" => $usuario->getApellidos(),
            "correo" => $usuario->getCorreo(),
            "correo_v" => $usuario->getCorreoV(),
            "edad" => $usuario->getEdad(),
            "objetivo_opt" => $usuario->getObjetivoOpt(),
            "objetivo_num" => $usuario->getObjetivoNum(),
         ];
      }

      return RespuestaController::format("200", $usuariosJSON);

   }

   /**
    * @Route("/buscar", name="app_usuario_buscar", methods={"POST"})
    * Metodo que devuelve un usuario en base a un ID o correo recibido mediante POST
    * @param Request $request
    * @param UsuarioRepository $usuarioRepository
    * @return Response con el usuario encontrado en formato JSON
    */
   public function buscar(Request $request, UsuarioRepository $usuarioRepository): Response
   {
      $data = json_decode($request->getContent(), true);

      if ($data) {
         if (isset($data['id'])) {
            $usuario = $usuarioRepository->find($data['id']);
         } elseif (isset($data['email'])) {
            $usuario = $usuarioRepository->findOneBy(['correo' => $data['email']]);
         } else {
            return RespuestaController::format("400", "ID o correo no recibidos");
         }
      }else {
         return RespuestaController::format("400", "No se han recibido datos");
      }

      $usuarioJSON = $this->usuarioJSON($usuario);

      return RespuestaController::format("200", $usuarioJSON);
   }

   /**
    * @Route("/crear", name="app_usuario_crear", methods={"POST"})
      * Metodo para crear un nuevo usuario con los datos recibidos mediante POST
      * @param Request $request
      * @param UsuarioRepository $usuarioRepository
      * @return Response con el usuario creado en formato JSON
    */
   public function crear(Request $request, UsuarioRepository $usuarioRepository): Response
   {
      $data = json_decode($request->getContent(), true);

      if (!$data) {
         return RespuestaController::format("400", "No se han recibido datos");
      }

      if (!$this->checkNuevoUsuario($data['correo'], $usuarioRepository)) {
         return RespuestaController::format("400", "Este usuario ya esta registrado");
      }

      $usuario = new Usuario();
      // Parámetros a recibir
      $usuario->setNombre($data['nombre']);
      $usuario->setApellidos($data['apellidos']);
      $usuario->setCorreo($data['correo']);
      $usuario->setEdad($data['edad']);
      $usuario->setAltura($data['altura']);

      // Parámetros fijos
      $usuario->setContrasena('null');
      $usuario->setObjetivoOpt('null');
      $usuario->setObjetivoNum(-1);
      $usuario->setCorreoV('false');
      $usuario->setRol();

      $usuarioRepository->add($usuario, true);

      $usuarioJSON = $this->usuarioJSON($usuario);

      $respuesta = RespuestaController::format("200", $usuarioJSON);
      return $respuesta;
   }

   /**
    * @Route("/editar/{id}", name="app_usuario_editar", methods={"PUT"})
    *
      * Metodo para editar un usuario en base a un ID recibido mediante PUT
      * @param $id
      * @param Request $request
      * @param UsuarioRepository $usuarioRepository
      * @return Response con el usuario editado en formato JSON
    */
   public function editar($id, Request $request, UsuarioRepository $usuarioRepository): Response
   {
      $data = json_decode($request->getContent(), true);

      if (!$data) {
         return RespuestaController::format("400", "No se han recibido datos");
      }

      $usuario = $usuarioRepository->find($id);

      if (!$usuario) {
         return RespuestaController::format("404", "Usuario a editar no encontrado");
      }

      $usuario->setNombre($data['nombre']);
      $usuario->setApellidos($data['apellidos']);
      $usuario->setAltura($data['altura']);
      $usuario->setObjetivoOpt($data['objetivo_opt']);
      $usuario->setObjetivoNum($data['objetivo_num']);

      $usuarioRepository->add($usuario, true);

      $usuarioJSON = $this->usuarioJSON($usuario);

      return RespuestaController::format("200", $usuarioJSON);
   }


   /**
    * @Route("/eliminar", name="app_usuario_eliminar", methods={"DELETE"})
      *
      * Metodo para eliminar un usuario en base a un ID o correo recibido mediante DELETE
      * @param Request $request
      * @param UsuarioRepository $usuarioRepository
      * @return Response con mensaje de confirmación
      * * importante: Metodo en desuso debido a que no hay administración desde la web
    */
   public function eliminar(Request $request, UsuarioRepository $usuarioRepository): Response
   {
      // $data = json_decode($request->getContent(), true);

      // if (!$data) {
      //    return RespuestaController::format("400", "No se han recibido datos");
      // }

      // if (isset($data['id'])) {
      //    $usuario = $usuarioRepository->find($data['id']);
      // } else {
      //    return RespuestaController::format("400", "ID o correo no recibidos");
      // }

      // if (!$usuario) {
      //    return RespuestaController::format("404", "Usuario no encontrado");
      // }

      // $usuarioRepository->remove($usuario, true);

      // return RespuestaController::format("200", "Usuario eliminado correctamente");

      return RespuestaController::format("400", "Metodo en desuso");
   }



   /**
    * *Importante: Estas funciones han sido creadas para facilitar el desarrollo del código
    */

   /**
    * Función para comprobar si un usuario ya está registrado
    * @param $correo
    * @param UsuarioRepository $usuarioRepository
    * @return bool
    */
   private function checkNuevoUsuario($correo, UsuarioRepository $usuarioRepository): bool
   {
      $usuario = $usuarioRepository->findOneBy(['correo' => $correo]);
      return $usuario ? false : true;
   }

   /**
    * Función que devuelve la información de un usuario en formato JSON lista para ser devuelta
    * @param Usuario $usuario
    * @return array
    */
   private function usuarioJSON(Usuario $usuario)
   {

      return [
         "id" => $usuario->getId(),
         "nombre" => $usuario->getNombre(),
         "apellidos" => $usuario->getApellidos(),
         "correo" => $usuario->getCorreo(),
         "correo_v" => $usuario->getCorreoV(),
         "edad" => $usuario->getEdad(),
         "altura" => $usuario->getAltura(),
         "objetivo_opt" => $usuario->getObjetivoOpt(),
         "objetivo_num" => $usuario->getObjetivoNum(),
      ];
   }
}
