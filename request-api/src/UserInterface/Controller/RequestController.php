<?php

namespace App\UserInterface\Controller;

use App\Application\Command\CreateRequestCommand;
use App\Domain\Entity\Request as DomainRequest;
use App\Domain\Repository\RequestRepository;
use App\UserInterface\Form\Domain\Entity\RequestType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('request')]
final class RequestController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $commandBus,
    ){
    }

    #[Route(name: 'app_domain_entity_request_index', methods: ['GET'])]
    public function index(RequestRepository $requestRepository): Response
    {
        return $this->render('request/index.html.twig', [
            'requests' => $requestRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_domain_entity_request_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $domainRequest = new DomainRequest();
        $form = $this->createForm(RequestType::class, $domainRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commandBus->dispatch(
                new CreateRequestCommand(
                    $form->get('agentId')->getData(),
                    $form->get('requesterId')->getData()
                )
            );

            return $this->redirectToRoute('app_domain_entity_request_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('request/new.html.twig', [
            'request' => $domainRequest,
            'form' => $form,
        ]);
    }

    #[Route('/{uuid}', name: 'app_domain_entity_request_show', methods: ['GET'])]
    public function show(DomainRequest $domainRequest): Response
    {
        return $this->render('request/show.html.twig', [
            'request' => $domainRequest,
        ]);
    }

    #[Route('/{uuid}/edit', name: 'app_domain_entity_request_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DomainRequest $domainRequest, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RequestType::class, $domainRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_domain_entity_request_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('request/edit.html.twig', [
            'request' => $domainRequest,
            'form' => $form,
        ]);
    }

    #[Route('/{uuid}', name: 'app_domain_entity_request_delete', methods: ['POST'])]
    public function delete(Request $request, DomainRequest $domainRequest, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$domainRequest->getUuid(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($domainRequest);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_domain_entity_request_index', [], Response::HTTP_SEE_OTHER);
    }
}
