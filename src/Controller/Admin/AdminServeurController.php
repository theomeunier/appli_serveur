<?php

namespace App\Controller\Admin;

use App\Entity\Serveurs;
use App\Entity\User;
use App\Form\ServeurType;
use App\Repository\ServeursRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/admin", name="admin_")
 */
class AdminServeurController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @route("/serveur", name="serveurs")
     */
    public function usersList(ServeursRepository $serveurs)
    {
        return $this->render("admin/serveurs/index.html.twig",[
            'serveurs' => $serveurs->findAll()
        ]);
    }

    /**
     * @Route("/nouveau/serveur", name="new_serveurs")
     */
    public function new(Request $request)
    {
        $serveurs = new Serveurs();
        $form = $this->createForm(ServeurType::class, $serveurs);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($serveurs);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'message.admin.controller.categories.new');
            return $this->redirectToRoute('admin_serveurs');
        }

        return $this->render('admin/serveurs/new.html.twig', [
            'category' => $serveurs,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @route ("/serveur/modifier/{id}", name="edit_serveurs")
     */
    public function editUser(Serveurs $serveurs, Request $request){
        $form = $this->createForm(ServeurType::class, $serveurs);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($serveurs);
            $entityManager->flush();

            $this->addFlash('success', "les paramettre du serveur a bien été modfier");
            return $this->redirectToRoute('admin_serveurs');
        }

        return $this->render('admin/serveurs/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/serveur/spprimer/{id}", name="delete_serveurs", methods={"DELETE"})
     */
    public function deleteUser(Serveurs $serveurs, Request $request)
    {

        if ($this->isCsrfTokenValid('delete' .$serveurs->getId(), $request->get('_token'))){

            $this->em->remove($serveurs);
            $this->em->flush();
        }

        return $this->redirectToRoute('admin_serveurs');
    }
}
