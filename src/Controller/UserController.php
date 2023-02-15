<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user,
    UserPasswordHasherInterface $userPasswordHasher, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $userRepository->save($user, true);
            //Si el usuario es ADMIN, lo redirigimos a la lista de usuarios y si no lo es, a la pagina principal
            if ($this->isGranted('ROLE_ADMIN')){
                return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
            }else{
                return $this->redirectToRoute('app_producto_index', [], Response::HTTP_SEE_OTHER);
            }

            
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isGranted('ROLE_ADMIN')){
            if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
                $userRepository->remove($user, true);
            }
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }else{
            //aqui habria que meter a la Entity User un atributo "activo" true/false para 
            //que el usuario que quiera eliminar su perfil deje de tener acceso a dicho 
            //perfil, pero sin eliminar dicho perfil de la base de datos
            return $this->redirectToRoute('app_logout', [], Response::HTTP_SEE_OTHER);
        }
    }
}
