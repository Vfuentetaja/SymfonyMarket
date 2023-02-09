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

#[Route('/pregunta')]
class PreguntaController extends AbstractController
{
    #[Route('/', name: 'app_pregunta_index', methods: ['GET'])]
    public function index(PreguntaRepository $preguntaRepository): Response
    {
        return $this->render('pregunta/index.html.twig', [
            'preguntas' => $preguntaRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_pregunta_new', methods: ['GET', 'POST'])]
    public function new(int $id,Request $request, PreguntaRepository $preguntaRepository,ProductoRepository $productoRepository): Response
    {
        $pregunta = new Pregunta();
        $pregunta->setFecha(new \DateTime());
        $pregunta->setUser($this->getUser());
        $pregunta->setNombreAutor($this->getUser()->getNombre());

        $producto=$productoRepository->findById($id);
        $pregunta->setProducto($producto[0]);

        $textoPregunta = $request->request->get('texto', '');
        $pregunta->setTexto($textoPregunta);

        $preguntaRepository->save($pregunta, true);

        return $this->redirectToRoute('app_producto_show', ['id'=>$id], Response::HTTP_SEE_OTHER);
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
