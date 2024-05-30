<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\UsuarioType;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Util\RespuestaController;

/*  TODO:
    hacer que si hay varios resultados con el mismo nombre se muestren todos
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

        if(!$usuarios){
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
            if ($usuarioRepository->findOneBy(['nombre' => $id])){
                $usuario = $usuarioRepository->findOneBy(['nombre' => $id]);
            }else {
                return RespuestaController::format("404", "Usuario no encontrado");
            }
        }

        $usuarioJSON = [
            "id" => $usuario->getId(),
            "nombre" => $usuario->getNombre(),
            "apellidos" => $usuario->getApellidos(),
            "correo" => $usuario->getCorreo(),
            "correo_v" => $usuario->getCorreoV(),
            "edad" => $usuario->getEdad(),
            "objetivo_opt" => $usuario->getObjetivoOpt(),
            "objetivo_num" => $usuario->getObjetivoNum(),
        ];

        $respuesta = RespuestaController::format("200", $usuarioJSON);

        return $respuesta;
    }

    /**
     * @Route("/new", name="app_usuario_new", methods={"POST"})
     */
    public function new(UsuarioRepository $usuarioRepository): Response
    {
        $usuarios = $usuarioRepository->findAll();

        if(!$usuarios){
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

}
