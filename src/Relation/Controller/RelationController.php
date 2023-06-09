<?php

declare(strict_types=1);

namespace App\Relation\Controller;

use App\Entity\Address;

use App\Entity\User;
use App\Form\ChangePasswordType;

use App\Relation\Domain\Entity\Relation;
use App\Relation\Form\RelationType;
use App\Repository\RelationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Logout\LogoutUrlGenerator;
use Webmozart\Assert\Assert;

#[Route('/relation'), IsGranted('ROLE_USER')]
class RelationController extends AbstractController
{
    public function __construct(
        private  RelationRepository $relationRepository
    )
    {
    }

    #[Route('/', name: 'relation_index', methods: ['GET'])]
    public function  index():Response{

        $relation = $this->relationRepository->findAll()[0];
        Assert($relation instanceof Relation);
        dd($relation->getAddresses()->toArray(),$relation->getContacts()->toArray());


    }
    
    #[Route('/new', name: 'relation_new', methods: ['GET', 'POST'])]
    public function new(
        #[CurrentUser] User $user,
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
        $relation = new Relation();
        $address = new Address();
        $address->setName('pampore');

        $relation->getAddresses()->add($address);

        $form = $this->createForm(RelationType::class, $relation);
        $form->handleRequest($request);


        // dd($form);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'user.updated_successfully');

            return $this->redirectToRoute('relation_new');
        }

        return $this->render('relation/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id<\d+>}/edit', methods: ['GET', 'POST'], name: 'relation_edit')]
    // #[IsGranted('edit', subject: 'relation', message: 'Posts can only be edited by their authors.')]
    public function edit(Request $request, Relation $relation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RelationType::class, $relation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'relation$relation.updated_successfully');

            return $this->redirectToRoute('admin_relation$relation_edit', ['id' => $relation->getId()]);
        }

        return $this->render('relation/edit.html.twig', [
            'post' => $relation,
            'form' => $form,
        ]);
    }

    /**
     * Deletes a Post entity.
     */
    #[Route('/{id}/delete', methods: ['POST'], name: 'admin_post_delete')]
    #[IsGranted('delete', subject: 'relation')]
    public function delete(Request $request, Relation $relation, EntityManagerInterface $entityManager): Response
    {
        /** @var string|null $token */
        $token = $request->request->get('token');

        if (!$this->isCsrfTokenValid('delete', $token)) {
            return $this->redirectToRoute('admin_post_index');
        }

        // Delete the tags associated with this blog post. This is done automatically
        // by Doctrine, except for SQLite (the database used in this application)
        // because foreign key support is not enabled by default in SQLite
        $relation->getAddresses()->clear();

        $entityManager->remove($relation);
        $entityManager->flush();

        $this->addFlash('success', 'post.deleted_successfully');

        return $this->redirectToRoute('admin_post_index');
    }
}
