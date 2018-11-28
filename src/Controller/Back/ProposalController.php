<?php

namespace App\Controller\Back;

use App\Entity\Proposal;
use App\Form\ProposalType;
use App\Repository\ProposalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("back/proposal")
 */
class ProposalController extends AbstractController
{
    /**
     * @Route("/", name="proposal_index", methods="GET")
     */
    public function index(ProposalRepository $proposalRepository): Response
    {
        $proposals = $proposalRepository->findBy([
            'deleted' => false
        ]);

        return $this->render(
            'proposal/index.html.twig',
            [
                "proposals"     => $proposals,
            ]
        );
    }

    /**
     * @Route("/new", name="proposal_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $proposal = new Proposal();
        $form = $this->createForm(ProposalType::class, $proposal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($proposal);
            $em->flush();

            return $this->redirectToRoute('proposal_index');
        }

        return $this->render('proposal/new.html.twig', [
            'proposal' => $proposal,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="proposal_show", methods="GET")
     */
    public function show(Proposal $proposal): Response
    {
        return $this->render('proposal/show.html.twig', ['proposal' => $proposal]);
    }

    /**
     * @Route("/{slug}/edit", name="proposal_edit", methods="GET|POST")
     */
    public function edit(Request $request, Proposal $proposal): Response
    {
        $form = $this->createForm(ProposalType::class, $proposal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('proposal_index', ['id' => $proposal->getId()]);
        }

        return $this->render('proposal/edit.html.twig', [
            'proposal' => $proposal,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="proposal_delete", methods="DELETE")
     */
    public function delete(Request $request, Proposal $proposal): Response
    {
        if ($this->isCsrfTokenValid('delete'.$proposal->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $proposal->setDeleted(1);
            $em->flush();
        }

        return $this->redirectToRoute('proposal_index');
    }
}
