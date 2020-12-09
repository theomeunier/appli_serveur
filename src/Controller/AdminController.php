<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditeUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @route("/utilisateur", name="utilisateurs")
     */
    public function usersList(UserRepository $users)
    {
        return $this->render("admin/users/index.html.twig",[
            'users' => $users->findAll()
        ]);
    }

    /**
     * @route ("/utilisateur/modifier/{id}", name="edit_user")
     */
    public function editUser(User $user, Request $request){
        $form = $this->createForm(EditeUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('message', "L'utilisateur a bien été modfier");
            return $this->redirectToRoute('admin_utilisateurs');
        }

        return $this->render('admin/users/edit.html.twig', [
            'userFrom' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete_user/{id}", name="delete_user", methods={"DELETE"})
     */
    public function deleteUser(User $user, Request $request)
    {

        if ($this->isCsrfTokenValid('delete' .$user->getId(), $request->get('_token'))){

            $this->em->remove($user);
            $this->em->flush();
        }

        return $this->redirectToRoute('admin_utilisateurs');
    }
}
