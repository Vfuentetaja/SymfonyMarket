<?php

namespace App\Controller;

use App\Entity\Respuesta;
use App\Form\RespuestaType;
use App\Repository\RespuestaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/respuesta')]
class RespuestaController extends AbstractController
{
    #[Route('/', name: 'app_respuesta_index', methods: ['GET'])]
    public function index(RespuestaRepository $respuestaRepository): Response
    {
        return $this->render('respuesta/index.html.twig', [
            'respuestas' => $respuestaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_respuesta_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RespuestaRepository $respuestaRepository): Response
    {
        $respuestum = new Respuesta();
        $form = $this->createForm(RespuestaType::class, $respuestum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $respuestaRepository->save($respuestum, true);

            return $this->redirectToRoute('app_respuesta_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('respuesta/new.html.twig', [
            'respuestum' => $respuestum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_respuesta_show', methods: ['GET'])]
    public function show(Respuesta $respuestum): Response
    {
        return $this->render('respuesta/show.html.twig', [
            'respuestum' => $respuestum,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_respuesta_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Respuesta $respuestum, RespuestaRepository $respuestaRepository): Response
    {
        $form = $this->createForm(RespuestaType::class, $respuestum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $respuestaRepository->save($respuestum, true);

            return $this->redirectToRoute('app_respuesta_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('respuesta/edit.html.twig', [
            'respuestum' => $respuestum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_respuesta_delete', methods: ['POST'])]
    public function delete(Request $request, Respuesta $respuestum, RespuestaRepository $respuestaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$respuestum->getId(), $request->request->get('_token'))) {
            $respuestaRepository->remove($respuestum, true);
        }

        return $this->redirectToRoute('app_respuesta_index', [], Response::HTTP_SEE_OTHER);
    }
}
