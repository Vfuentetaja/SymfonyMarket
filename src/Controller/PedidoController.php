<?php

namespace App\Controller;

use App\Entity\Pedido;
use App\Entity\Item;
use App\Entity\Producto;
use App\Form\PedidoType;
use App\Repository\PedidoRepository;
use App\Repository\ItemRepository;
use App\Repository\ProductoRepository;
use App\Service\PedidoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pedido')]
class PedidoController extends AbstractController
{
    #[Route('/', name: 'app_pedido_index_all', methods: ['GET'])]
    public function indexAll(PedidoRepository $pedidoRepository): Response
    {
         return $this->render('pedido/index.html.twig', [
            'pedidos' => $pedidoRepository->findAll(),
        ]); 
    }

    #[Route('/{id}', name: 'app_pedido_index', methods: ['GET'], requirements:['id'=>'\d+'])]
    public function index(int $id,PedidoRepository $pedidoRepository): Response
    {
        return $this->render('pedido/index.html.twig', [
            'pedidos' => $pedidoRepository->findAllByUser($id),
        ]);
    }



    #[Route('/new', name: 'app_pedido_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PedidoRepository $pedidoRepository, ProductoRepository $productoRepository): Response
    {
        $pedido = new Pedido();
        $pedido->setFechaPedido(new \DateTime());
        $pedido->setIdUsuario($this->getUser()->getId());
        $pedido->setDestinatario($this->getUser()->getNombre());
        $pedido->setDireccionDestinatario($this->getUser()->getDireccion());

        //sacamos los datos de la URL
        $datosCar = $_GET['array']; 
        $arr=explode("} {", $datosCar);
        $arrDatos=array("");
        for($i=0; $i<count($arr); $i++){
            $arrAux = explode(",", $arr[$i]);
            array_push($arrDatos, $arrAux);
        }
        $arrFinal=array();
        for($i=1; $i<count($arr); $i++){
            for($j=0; $j<3; $j++){
                $arrAux = explode(":", $arrDatos[$i][$j]);
                array_push($arrFinal, $arrAux);
            }
        }
        //creamos el pedido con los datos que hemos sacado
        for($i=0; $i<count($arrFinal); $i+=3){
                $item1= new Item();
                $producto1= $productoRepository->findOneByNombre($arrFinal[$i][1]);
                $item1->setProducto($producto1);
                $item1->setCantidad($arrFinal[$i+2][1]);
                $item1->setPedido($pedido);
                $pedido->addItem($item1);
            }

        $pedidoRepository->save($pedido, true);

        return $this->redirectToRoute('app_pedido_show', ['id'=>$pedido->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/show', name: 'app_pedido_show', methods: ['GET'], requirements:['id'=>'\d+'])]
    public function show(Pedido $pedido,PedidoService $pedidoService): Response
    {
        $total=$pedidoService->calcularTotal($pedido);
        return $this->render('pedido/show.html.twig', [
            'pedido' => $pedido,'total'=>$total,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pedido_edit', methods: ['GET', 'POST'], requirements:['id'=>'\d+'])]
    public function edit(Request $request, Pedido $pedido, PedidoRepository $pedidoRepository): Response
    {
        $form = $this->createForm(PedidoType::class, $pedido);
        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {
            $pedidoRepository->save($pedido, true);
            if (in_array('ROLE_ADMIN',$this->getUser()->getRoles())){
                return $this->redirectToRoute('app_pedido_index_all', [], Response::HTTP_SEE_OTHER);
            }else{
                return $this->redirectToRoute('app_pedido_index', ['id'=>$pedido->getIdUsuario()], Response::HTTP_SEE_OTHER);
            } 
        }

        return $this->renderForm('pedido/edit.html.twig', [
            'pedido' => $pedido,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pedido_delete', methods: ['POST'], requirements:['id'=>'\d+'])]
    public function delete(Request $request, Pedido $pedido, PedidoRepository $pedidoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pedido->getId(), $request->request->get('_token'))) {

            $pedido=$pedidoRepository->findOneById($pedido->getId());
            $pedidoRepository->remove($pedido, true); //al eliminar el pedido, como esta 
                //configurado en cascade, se eliminan tambien todos los items asociados a 
                //ese pedido de la tabla Items
        } 

        if (in_array('ROLE_ADMIN',$this->getUser()->getRoles())){
            return $this->redirectToRoute('app_pedido_index_all', [], Response::HTTP_SEE_OTHER);
        }else{
            return $this->redirectToRoute('app_pedido_index', [], Response::HTTP_SEE_OTHER);
        }
        
    }

    #[Route('/{id}/{id_item}', name: 'app_item_delete', methods: ['POST'], requirements:['id'=>'\d+'])]
    public function deleteItem(Request $request, Pedido $pedido,int $id_item,ItemRepository $itemRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pedido->getId(), $request->request->get('_token'))) {

            
            $item=$itemRepository->findOneById($id_item);
            $itemRepository->remove($item,true);
        } 

        return $this->redirectToRoute('app_pedido_show', ['id'=>$pedido->getId()], Response::HTTP_SEE_OTHER);

        
    }

    #[Route('/pago', name: 'app_pedido_pago', methods: ['GET'])]
    public function formPagar(): Response
    {
        return $this->render('pedido/pago.html.twig', []); 
    }
}
