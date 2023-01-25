<?php

namespace App\Controller;

use App\Entity\Pedido;
use App\Form\PedidoType;
use App\Repository\PedidoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pedido')]
class PedidoController extends AbstractController
{
    #[Route('/', name: 'app_pedido_index', methods: ['GET'])]
    public function index(PedidoRepository $pedidoRepository): Response
    {
        return $this->render('pedido/index.html.twig', [
            'pedidos' => $pedidoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pedido_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PedidoRepository $pedidoRepository): Response
    {
        $pedido = new Pedido();
        $form = $this->createForm(PedidoType::class, $pedido);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pedidoRepository->save($pedido, true);

            return $this->redirectToRoute('app_pedido_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pedido/new.html.twig', [
            'pedido' => $pedido,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pedido_show', methods: ['GET'])]
    public function show(Pedido $pedido): Response
    {
        return $this->render('pedido/show.html.twig', [
            'pedido' => $pedido,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pedido_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pedido $pedido, PedidoRepository $pedidoRepository): Response
    {
        $form = $this->createForm(PedidoType::class, $pedido);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pedidoRepository->save($pedido, true);

            return $this->redirectToRoute('app_pedido_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pedido/edit.html.twig', [
            'pedido' => $pedido,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pedido_delete', methods: ['POST'])]
    public function delete(Request $request, Pedido $pedido, PedidoRepository $pedidoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pedido->getId(), $request->request->get('_token'))) {
            $pedidoRepository->remove($pedido, true);
        }

        return $this->redirectToRoute('app_pedido_index', [], Response::HTTP_SEE_OTHER);
    }
}
