<?php

namespace App\Controller;

use App\Entity\Respuesta;
use App\Entity\Pregunta;
use App\Form\RespuestaType;
use App\Repository\RespuestaRepository;
use App\Repository\PreguntaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;

#[Route('/respuesta')]
class RespuestaController extends AbstractController
{
    #[Route('/{id}', name: 'app_respuesta_index', methods: ['GET', 'POST'],requirements:['id'=>'\d+'])]
    public function index(int $id,RespuestaRepository $respuestaRepository): Response
    {
        $recuperadas=$respuestaRepository->findByIdPregunta($id);

        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new DateTimeNormalizer(),new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->normalize($recuperadas,'json',[AbstractNormalizer::ATTRIBUTES => ['id','texto','nombreAutor','fecha','User'=>['id'],'pregunta'=>['id']]]);
        return new JsonResponse($data);
    }

    #[Route('/usuario/{id}', name: 'app_respuesta_usuario_index', methods: ['GET', 'POST'],requirements:['id'=>'\d+'])]
    public function indexRespuestasUsuario(int $id,RespuestaRepository $respuestaRepository): Response
    {
        $recuperadas=$respuestaRepository->findByIdPregunta($id);

        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new DateTimeNormalizer(),new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->normalize($recuperadas,'json',[AbstractNormalizer::ATTRIBUTES => ['id','texto','nombreAutor','fecha','User'=>['id'],'pregunta'=>['id']]]);
        return new JsonResponse($data);
    }

    #[Route('/new', name: 'app_respuesta_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RespuestaRepository $respuestaRepository,PreguntaRepository $preguntaRepository): Response
    {
        $respuesta = new Respuesta();
        
        $idPregunta=$_REQUEST['idPregunta'];
        $pregunta= $preguntaRepository->findById($idPregunta);
        $textoRespuesta=$_REQUEST['textoRespuesta'];

        $respuesta->setTexto($textoRespuesta);
        $respuesta->setNombreAutor($this->getUser()->getNombre());
        $respuesta->setFecha(new \DateTime());
        $respuesta->setPregunta($pregunta[0]);
        $respuesta->setUser($this->getUser());

        $respuestaRepository->save($respuesta, true);

        $recuperadas=$respuestaRepository->findByIdPregunta($idPregunta);

        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new DateTimeNormalizer(),new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->normalize($recuperadas,'json',[AbstractNormalizer::ATTRIBUTES => ['id','texto','nombreAutor','fecha','User'=>['id'],'pregunta'=>['id']]]);
        return new JsonResponse($data);

    }

/*     #[Route('/{id}', name: 'app_respuesta_show', methods: ['GET'])]
    public function show(Respuesta $respuestum): Response
    {
        return $this->render('respuesta/show.html.twig', [
            'respuestum' => $respuestum,
        ]);
    } */

    #[Route('/{id}/edit', name: 'app_respuesta_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Respuesta $respuestum, RespuestaRepository $respuestaRepository): Response
    {
        $form = $this->createForm(RespuestaType::class, $respuestum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             $respuestaRepository->save($respuestum, true);

            return $this->redirectToRoute('app_producto_show', ['id' => $respuestum->getPregunta()->getProducto()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('respuesta/edit.html.twig', [
            'respuestum' => $respuestum,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_respuesta_delete', methods: ['POST'],requirements:['id'=>'\d+'])]
    public function delete(int $id,Request $request, Respuesta $respuestum, RespuestaRepository $respuestaRepository): Response
    {
        $respuesta=$respuestaRepository->findOneById($id);
        $pregunta=$respuesta->getPregunta();
        $respuestaRepository->remove($respuesta, true);
        $recuperadas=$respuestaRepository->findByIdPregunta($pregunta->getId());

        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new DateTimeNormalizer(),new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->normalize($recuperadas,'json',[AbstractNormalizer::ATTRIBUTES => ['id','texto','nombreAutor','fecha','User'=>['id'],'pregunta'=>['id']]]);
        return new JsonResponse($data);
    }
}
