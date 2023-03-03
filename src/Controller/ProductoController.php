<?php

namespace App\Controller;

use App\Entity\Producto;
use App\Form\ProductoType;
use App\Repository\ProductoRepository;
use App\Service\FileUploader;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/producto')]
class ProductoController extends AbstractController
{
    #[Route('/', name: 'app_producto_index', methods: ['GET'])]
    public function index(ProductoRepository $productoRepository): Response
    {
        return $this->render('producto/index.html.twig', [
            'productos' => $productoRepository->findAll(),
        ]);
    }
    #[Route('/siteMap', name: 'app_siteMap', methods: ['GET', 'POST'])]
    
    public function mapa(Request $request){
        return $this->render('producto/siteMap.html.twig', []);
    }

    #[Route('/new', name: 'app_producto_new', methods: ['GET', 'POST'])]
    public function new(Request $request,FileUploader $fileUploader, ProductoRepository $productoRepository): Response
    {
        $producto = new Producto();
        $form = $this->createForm(ProductoType::class, $producto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imagen = $form->get('imagen1')->getData();
            if ($imagen) {
                $imagenFileName = $fileUploader->upload($imagen);
                $producto->setImagen($imagenFileName);
            }

            $productoRepository->save($producto, true);

            return $this->redirectToRoute('app_producto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('producto/new.html.twig', [
            'producto' => $producto,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_producto_show', methods: ['GET'],requirements:['id'=>'\d+'])]
    public function show(Producto $producto): Response
    {
        return $this->render('producto/show.html.twig', [
            'producto' => $producto,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_producto_edit', methods: ['GET', 'POST'],requirements:['id'=>'\d+'])]
    public function edit(Request $request, Producto $producto, ProductoRepository $productoRepository,FileUploader $fileUploader): Response
    {
        $form = $this->createForm(ProductoType::class, $producto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imagen = $form->get('imagen1')->getData();
	        if ($imagen) {
		        $imagenFileName = $fileUploader->upload($imagen); 
		        $producto->setImagen($imagenFileName); 
	        }
            $productoRepository->save($producto, true);

            return $this->redirectToRoute('app_producto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('producto/edit.html.twig', [
            'producto' => $producto,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_producto_delete', methods: ['GET','POST'],requirements:['id'=>'\d+'])]
    public function delete(Request $request, Producto $producto, ProductoRepository $productoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$producto->getId(), $request->request->get('_token'))) {
            $productoRepository->remove($producto, true);
        }

        return $this->redirectToRoute('app_producto_search_all', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/buscar', name: 'app_producto_search_all')]
	public function searchAll(ProductoRepository $productoRepository): Response
	{
        $textoBuscado=null;
        $productosRecuperados=$productoRepository->searchText($textoBuscado);
        if($this->getUser()==null){
            return $this->render('producto/lista_productos_usuarios.html.twig', [
                'productos' => $productosRecuperados,
            ]);
        }else if (in_array('ROLE_ADMIN',$this->getUser()->getRoles())){
            return $this->render('producto/lista_productos.html.twig', [
                'productos' => $productosRecuperados,
            ]);
        }else{
            return $this->render('producto/lista_productos_usuarios.html.twig', [
                'productos' => $productosRecuperados,
            ]);
        }
    }

    //en esta funcion, en lugar de hacer un requirements hemos modificado el routes.yaml
    #[Route('/buscar', name: 'app_producto_search', methods: ['POST','GET'])]
	public function search(Request $request, ProductoRepository $productoRepository): Response
	{
        $textoBuscado=$request->request->get('textoBuscado',null);
        $productosRecuperados=$productoRepository->searchText($textoBuscado);
        if($this->getUser()==null){
            return $this->render('producto/lista_productos_usuarios.html.twig', [
                'productos' => $productosRecuperados,
            ]);
        }else if (in_array('ROLE_ADMIN',$this->getUser()->getRoles())){
            return $this->render('producto/lista_productos.html.twig', [
                'productos' => $productosRecuperados,
            ]);
        }else{
            return $this->render('producto/lista_productos_usuarios.html.twig', [
                'productos' => $productosRecuperados,
            ]);
        }

    }

    const ELEMENTOS_POR_PAGINA=6;

    #[Route('/regalos/{pagina}', name: 'app_producto_regalos', 
    defaults: ['pagina'=>1],requirements:['pagina'=>'\d+'], methods: ['GET'])]
	public function listaProductosRegalos(Request $request,int $pagina,ProductoRepository $productoRepository): Response
	{
        $productosRecuperados=$productoRepository->buscarProductosRegalos($pagina,self::ELEMENTOS_POR_PAGINA);
        return $this->render('producto/regalos.html.twig', [
            'productos' => $productosRecuperados,'pagina_actual1' => $pagina,
        ]);
    }

    #[Route('/ropa/{pagina}', name: 'app_producto_ropa', 
    defaults: ['pagina'=>1],requirements:['pagina'=>'\d+'], methods: ['GET'])]
	public function listaProductosRopa(Request $request,int $pagina,ProductoRepository $productoRepository): Response
	{
        $productosRecuperados=$productoRepository->buscarProductosRopa($pagina,self::ELEMENTOS_POR_PAGINA);
        return $this->render('producto/ropa.html.twig', [
            'productos' => $productosRecuperados,'pagina_actual1' => $pagina,
        ]);
    }

    #[Route('/decoracion/{pagina}', name: 'app_producto_decoracion', 
    defaults: ['pagina'=>1],requirements:['pagina'=>'\d+'], methods: ['GET'])]
	public function listaProductosDecoracion(Request $request,int $pagina,ProductoRepository $productoRepository): Response
	{
        $productosRecuperados=$productoRepository->buscarProductosDecoracion($pagina,self::ELEMENTOS_POR_PAGINA);
        return $this->render('producto/decoracion.html.twig', [
            'productos' => $productosRecuperados,'pagina_actual1' => $pagina,
        ]);
    }

    #[Route('/figuras/{pagina}', name: 'app_producto_figuras', 
    defaults: ['pagina'=>1],requirements:['pagina'=>'\d+'], methods: ['GET'])]
	public function listaProductosFiguras(Request $request,int $pagina,ProductoRepository $productoRepository): Response
	{
        $productosRecuperados=$productoRepository->buscarProductosFiguras($pagina,self::ELEMENTOS_POR_PAGINA);
        return $this->render('producto/figuras.html.twig', [
            'productos' => $productosRecuperados,'pagina_actual1' => $pagina,
        ]);
    }
}
