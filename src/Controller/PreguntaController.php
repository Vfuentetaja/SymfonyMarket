<?php

namespace App\Controller;

use App\Entity\Pregunta;
use App\Form\PreguntaType;
use App\Repository\PreguntaRepository;
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
    #[Route('/', name: 'app_pregunta_index', methods: ['GET'])]
    public function index(PreguntaRepository $preguntaRepository): Response
    {
        $pregunt= $preguntaRepository->findAll();
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new DateTimeNormalizer(),new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->normalize($pregunt,'json',[AbstractNormalizer::ATTRIBUTES => ['id','texto','nombreAutor','fecha']]);
        return new JsonResponse($data);
    }

    #[Route('/usuario', name: 'app_pregunta_usuario_index', methods: ['GET'])]
    public function indexPreguntasUsuario(PreguntaRepository $preguntaRepository): Response
    {
        $pregunt= $preguntaRepository->findByUser($this->getUser());
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new DateTimeNormalizer(),new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->normalize($pregunt,'json',[AbstractNormalizer::ATTRIBUTES => ['id','texto','nombreAutor','fecha']]);
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

        return $this->redirectToRoute('app_producto_show', ['id'=>$id], Response::HTTP_SEE_OTHER);

        //$textoPregunta = $request->request->get('texto', '');
        
        //$pregunt= $preguntaRepository->findById(3);
        //$pregunt= $preguntaRepository->findAll();
        //dd(gettype($pregunt[0]));

        /* $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->normalize($pregunt,'json',[AbstractNormalizer::ATTRIBUTES => ['id','texto','nombreAutor']]);
        return new JsonResponse($data); */
        

        /* $pregunt= $preguntaRepository->findById(3);
        dd($pregunt); */
        //$texto= "xxxx";
        //echo $texto;
        //echo json_encode($preguntas);
        //return $preguntas;
        

        //new JsonResponse, which firstly looking for the serializer in your container. So you have at least two options
        //Install Symfony or JMS serializer, it will simplify your life
        
        //return new JsonResponse(['pregunt' => $pregunt]);
        //return $this->json($pregunt);
        

        //return new JsonResponse::fromJsonString($serializer->serialize($pregunt, 'json'));
        //$encoder = new JsonEncoder();
        /* $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->normalize($pregunt[0],null,[AbstractNormalizer::ATTRIBUTES => ['id','texto']]);
        dd($data);  */
        //return new JsonResponse($data);
        //, null, [AbstractNormalizer::ATTRIBUTES => ['familyName', 'company' => ['name']]]
        //$data = $serializer->serialize($pregunt, 'json');
        //dd($data);
        
        //return new Response($pregunt);
        /* $pregunt = $serializer->serialize($preguntaRepository->findById(3), 'json');
        $response = new Response(
            $pregunt,
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
         );
        return $response;   */
        //return new JsonResponse($serializer->serialize($pregunt, 'json'));
        //return new JsonResponse(['pregunt' => $pregunt]);
    }

    #[Route('/{id}', name: 'app_pregunta_show', methods: ['GET'])]
    public function show(Pregunta $preguntum): Response
    {
        return $this->render('pregunta/show.html.twig', [
            'preguntum' => $preguntum,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pregunta_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pregunta $preguntum, PreguntaRepository $preguntaRepository): Response
    {
        $form = $this->createForm(PreguntaType::class, $preguntum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $preguntaRepository->save($preguntum, true);

            return $this->redirectToRoute('app_pregunta_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pregunta/edit.html.twig', [
            'preguntum' => $preguntum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pregunta_delete', methods: ['POST'])]
    public function delete(Request $request, Pregunta $preguntum, PreguntaRepository $preguntaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$preguntum->getId(), $request->request->get('_token'))) {
            $preguntaRepository->remove($preguntum, true);
        }

        return $this->redirectToRoute('app_pregunta_index', [], Response::HTTP_SEE_OTHER);
    }
}
