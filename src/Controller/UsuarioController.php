<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Util\RespuestaController;

/*  TODO:
    hacer que si hay varios resultados con el mismo nombre se muestren todos
    cambiar los codigo de errores
    hacer resto de metodos faltantes para el usuario
*/

/**
 * @Route("/usuario")
 */
class UsuarioController extends AbstractController
{
   /**
    * @Route("/", name="app_usuario_index", methods={"GET"})
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
    * @Route("/{id}", name="app_usuario_buscar", methods={"GET"})
    */
   public function buscar($id, UsuarioRepository $usuarioRepository): Response
   {
      $usuario = $usuarioRepository->find($id);

      if (!$usuario) {
         // Si no encuentro por ID busco por nombre
         if ($usuarioRepository->findOneBy(['nombre' => $id])) {
            $usuario = $usuarioRepository->findOneBy(['nombre' => $id]);
         } else {
            return RespuestaController::format("404", "Usuario no encontrado");
         }
      }

      $usuarioJSON = $this->usuarioJSON($usuario);

      return RespuestaController::format("200", $usuarioJSON);
   }

   /**
    * @Route("/crear", name="app_usuario_crear", methods={"POST"})
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
      $usuario->setContrasena($data['contrasena']);
      $usuario->setObjetivoOpt($data['objetivo_opt']);
      $usuario->setObjetivoNum($data['objetivo_num']);

      // Parámetros fijos
      $usuario->setCorreoV('false');
      $usuario->setRol();

      $usuarioRepository->add($usuario, true);

      $usuarioJSON = $this->usuarioJSON($usuario);

      $respuesta = RespuestaController::format("200", $usuarioJSON);
      return $respuesta;
   }

   /**
    * @Route("/editar/{id}", name="app_usuario_editar", methods={"PUT"})
    */
   public function editar($id, Request $request, UsuarioRepository $usuarioRepository): Response
   {
      $data = json_decode($request->getContent(), true);

      if (!$data) {
         return RespuestaController::format("400", "No se han recibido datos");
      }

      // Buscar usuario a editar por ID
      $usuario = $usuarioRepository->find($id);

      if (!$usuario) {
         return RespuestaController::format("404", "Usuario a editar no encontrado");
      }

      // Comprobar que el correo del usuario a editar coincide con el correo proporcionado en los datos
      if ($usuario->getCorreo() !== $data['correo']) {
         return RespuestaController::format("400", "El correo no se puede cambiar");
      }

      // Parámetros a recibir
      $usuario->setNombre($data['nombre']);
      $usuario->setApellidos($data['apellidos']);
      $usuario->setCorreo($data['correo']);
      $usuario->setEdad($data['edad']);
      $usuario->setAltura($data['altura']);
      $usuario->setContrasena($data['contrasena']);
      $usuario->setObjetivoOpt($data['objetivo_opt']);
      $usuario->setObjetivoNum($data['objetivo_num']);

      $usuarioRepository->add($usuario, true);

      $usuarioJSON = $this->usuarioJSON($usuario);

      return RespuestaController::format("200", $usuarioJSON);
   }


   /**
    * @Route("/eliminar", name="app_usuario_eliminar", methods={"DELETE"})
    */
   public function eliminar(Request $request, UsuarioRepository $usuarioRepository): Response
   {
      $data = json_decode($request->getContent(), true);

      if (!$data) {
         return RespuestaController::format("400", "No se han recibido datos");
      }

      if (isset($data['id'])) {
         // Buscar usuario por ID
         $usuario = $usuarioRepository->find($data['id']);
      } elseif (isset($data['correo'])) {
         // Buscar usuario por correo
         $usuario = $usuarioRepository->findOneBy(['correo' => $data['correo']]);
      } else {
         return RespuestaController::format("400", "ID o correo no recibidos");
      }

      if (!$usuario) {
         return RespuestaController::format("404", "Usuario no encontrado");
      }

      $usuarioRepository->remove($usuario, true);

      return RespuestaController::format("200", "Usuario eliminado correctamente");
   }



   //Funciones extras
   private function checkNuevoUsuario($correo, UsuarioRepository $usuarioRepository): bool
   {
      $usuario = $usuarioRepository->findOneBy(['correo' => $correo]);
      return $usuario ? false : true;
   }

   private function usuarioJSON(Usuario $usuario)
   {

      $usuarioJSON = [
         "id" => $usuario->getId(),
         "nombre" => $usuario->getNombre(),
         "apellidos" => $usuario->getApellidos(),
         "correo" => $usuario->getCorreo(),
         "correo_v" => $usuario->getCorreoV(),
         "edad" => $usuario->getEdad(),
         "altura" => $usuario->getAltura(),
         "contrasena" => $usuario->getContrasena(),
         "objetivo_opt" => $usuario->getObjetivoOpt(),
         "objetivo_num" => $usuario->getObjetivoNum(),
      ];

      return $usuarioJSON;
   }
}
