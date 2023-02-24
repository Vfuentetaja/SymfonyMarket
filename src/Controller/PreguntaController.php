<?php

namespace App\Controller;

use App\Entity\Pregunta;
use App\Form\PreguntaType;
use App\Repository\PreguntaRepository;
use App\Repository\UserRepository;
use App\Repository\ProductoRepository;
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

#[Route('/pregunta')]
class PreguntaController extends AbstractController
{
    #[Route('/{id}', name: 'app_pregunta_index', methods: ['GET'],requirements:['id'=>'\d+'])]
    public function index(int $id,PreguntaRepository $preguntaRepository,ProductoRepository $productoRepository): Response
    {
        $producto=$productoRepository->findOneById($id);
        $pregunt= $preguntaRepository->findByProducto($producto);
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new DateTimeNormalizer(),new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->normalize($pregunt,'json',[AbstractNormalizer::ATTRIBUTES => ['id','texto','nombreAutor','fecha','User'=>['id']]]);
        return new JsonResponse($data);
    }

    #[Route('/usuario/{id}', name: 'app_pregunta_usuario_index', methods: ['GET'],requirements:['id'=>'\d+'])]
    public function indexPreguntasUsuario(int $id, PreguntaRepository $preguntaRepository, UserRepository $userRepository ): Response
    {
        if(in_array("ROLE_ADMIN",$this->getUser()->getRoles())){
            $user = $userRepository->findOneById($id);
            $pregunt= $preguntaRepository->findByUser($user);
        }else{
            $pregunt= $preguntaRepository->findByUser($this->getUser());
        }
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new DateTimeNormalizer(),new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->normalize($pregunt,'json',[AbstractNormalizer::ATTRIBUTES => ['id','texto','nombreAutor','fecha','User'=>['id']]]);
        return new JsonResponse($data);
    }

    #[Route('/new/{id}', name: 'app_pregunta_new', methods: ['GET', 'POST'])]
    public function new(int $id,Request $request, 
    PreguntaRepository $preguntaRepository,ProductoRepository $productoRepository): Response
    {
        $pregunta = new Pregunta();
        $pregunta->setFecha(new \DateTime());
        $pregunta->setUser($this->getUser());
        $pregunta->setNombreAutor($this->getUser()->getNombre());

        $producto=$productoRepository->findById($id);
        $pregunta->setProducto($producto[0]);

        $textoPregunta=$_REQUEST['texto'];
        $pregunta->setTexto($textoPregunta);
        $preguntaRepository->save($pregunta, true);

        $pregunta1= $preguntaRepository->findOneByFecha($pregunta->getFecha());
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new DateTimeNormalizer(),new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->normalize($pregunta1[0],'json',[AbstractNormalizer::ATTRIBUTES => ['id','texto','nombreAutor','fecha','User'=>['id']]]);
        return new JsonResponse($data);
    }

/*     #[Route('/{id}', name: 'app_pregunta_show', methods: ['GET'])]
    public function show(Pregunta $preguntum): Response
    {
        return $this->render('pregunta/show.html.twig', [
            'preguntum' => $preguntum,
        ]);
    } */

    #[Route('/edit/{id}', name: 'app_pregunta_edit', methods: ['GET', 'POST'],requirements:['id'=>'\d+'])]
    public function edit(int $id,Request $request, PreguntaRepository $preguntaRepository,ProductoRepository $productoRepository): Response
    {
        $idPregunta=$_REQUEST['idPregunta'];
        $textoPregunta=$_REQUEST['textoPregunta'];
        $pregunta=$preguntaRepository->findOneById($idPregunta);
        $pregunta->setTexto($textoPregunta);
        $preguntaRepository->save($pregunta, true);

        $producto=$productoRepository->findOneById($id);
        $pregunt= $preguntaRepository->findByProducto($producto);
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new DateTimeNormalizer(),new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->normalize($pregunt,'json',[AbstractNormalizer::ATTRIBUTES => ['id','texto','nombreAutor','fecha','User'=>['id']]]);
        return new JsonResponse($data);
    }

    #[Route('/usuario/edit', name: 'app_pregunta_usuario_edit', methods: ['GET', 'POST'])]
    public function editPreguntasUsuario(Request $request, PreguntaRepository $preguntaRepository,ProductoRepository $productoRepository): Response
    {
        $idPregunta=$_REQUEST['idPregunta'];
        $textoPregunta=$_REQUEST['textoPregunta'];
        $pregunta=$preguntaRepository->findOneById($idPregunta);
        $pregunta->setTexto($textoPregunta);
        $preguntaRepository->save($pregunta, true);

        $pregunt= $preguntaRepository->findByUser($this->getUser());
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new DateTimeNormalizer(),new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->normalize($pregunt,'json',[AbstractNormalizer::ATTRIBUTES => ['id','texto','nombreAutor','fecha','User'=>['id']]]);
        return new JsonResponse($data);

    }

    #[Route('/delete/{id}', name: 'app_pregunta_delete', methods: ['POST'],requirements:['id'=>'\d+'])]
    public function delete(int $id,Request $request, Pregunta $preguntum, PreguntaRepository $preguntaRepository): Response
    {
        $pregunta=$preguntaRepository->findOneById($id);
        $producto=$pregunta->getProducto();
       
        $preguntaRepository->remove($pregunta, true);
        
        $pregunt= $preguntaRepository->findByProducto($producto);
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new DateTimeNormalizer(),new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->normalize($pregunt,'json',[AbstractNormalizer::ATTRIBUTES => ['id','texto','nombreAutor','fecha','User'=>['id']]]);
        return new JsonResponse($data);
    }

    #[Route('/delete/usuario/{id}', name: 'app_pregunta_delete_usuario', methods: ['POST'],requirements:['id'=>'\d+'])]
    public function deletePreguntasUsuario(int $id,Request $request, Pregunta $preguntum, PreguntaRepository $preguntaRepository): Response
    {
        $pregunta=$preguntaRepository->findOneById($id);
       
        $preguntaRepository->remove($pregunta, true);
        
        $pregunt= $preguntaRepository->findByUser($this->getUser());
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new DateTimeNormalizer(),new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->normalize($pregunt,'json',[AbstractNormalizer::ATTRIBUTES => ['id','texto','nombreAutor','fecha','User'=>['id']]]);
        return new JsonResponse($data);
    }
}
