<?php

namespace App\Controller;

use index;
use App\Entity\Pays;
use App\Entity\User;
use App\Entity\Cepages;
use App\Entity\Regions;
use App\Entity\Bouteille;
use App\Form\CaveBouteilleType;
use App\Repository\PaysRepository;
use App\Repository\CepagesRepository;
use App\Repository\RegionsRepository;
use App\Repository\BouteilleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\form;

final class CaveController extends AbstractController
{
    #[Route('/regions/by-pays/{id}', name: 'regions_by_pays', methods: ['GET'])]
    public function regionsByPays(Pays $pays): JsonResponse
    {
        $regions = $pays->getRegions();

        $result = [];
        foreach ($regions as $region) {
            $result[] = [
                'id' => $region->getId(),
                'region' => $region->getRegion(),
            ];
        }

        return new JsonResponse($result);
    }



    #[Route('/cave', name: 'app_cave')]
    public function index(BouteilleRepository $bouteilleRepository): Response
    {
        $user = $this->getUser();
        $bouteille = $bouteilleRepository->findBy(['user' => $user]);
        return $this->render('cave/index.html.twig', [
            'bouteilles' => $bouteille,
            'owner' => $user,
            'readonly' => true
        ]);
    }

    #[Route('/cave/{id}', name: 'user_cave')]
    public function userCave(User $users, BouteilleRepository $bouteilleRepository): Response
    {
        $bouteille = $bouteilleRepository->findBy(['user' => $users]);
        return $this->render('cave/index.html.twig', [
            'bouteilles' => $bouteille,
            'owner' => $users,
            'readonly' => true
        ]);
    }

    #[Route('/other_cave}', name: 'other_cave')]
    public function otherCave(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('cave/other_cave.html.twig', [
            'users' => $users
        ]);
    }




    #[Route('/ajout_bouteille', name: 'create_bouteille')]
    public function create_bouteille(Request $request, EntityManagerInterface $entityManager, CepagesRepository $cepagesRepository, RegionsRepository $regionRepository): Response
    {
        $bouteille = new Bouteille();

        $form = $this->createForm(CaveBouteilleType::class, $bouteille, [
            'region' => $regionRepository->createQueryBuilder('r')
                ->select('r.id, r.region')
                ->getQuery()
                ->getResult(\Doctrine\ORM\Query::HYDRATE_SCALAR),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cepageText = $form->get('cepage')->getData();

            if ($cepageText) {
                // Recherche d’un cépage existant (par nom exact)
                $cepage = $cepagesRepository->findOneBy(['cepage' => $cepageText]);

                if (!$cepage) {
                    $cepage = new Cepages();
                    $cepage->setCepage($cepageText);
                    $entityManager->persist($cepage);
                }

                // Affecte le cépage à la bouteille
                $bouteille->setCepage($cepage);
            }
            $bouteille->setUser($this->getUser());

            $regionId = $form->get('region')->getData();
            $region = $regionRepository->find($regionId);
            $bouteille->setRegion($region);

            $bouteille->setUser($this->getUser());
            $entityManager->persist($bouteille);
            $entityManager->flush();

            return $this->redirectToRoute('app_cave');
        }
        return $this->render('cave/create_bouteille.html.twig', [
            'CaveBouteilleType' => $form->createView(),
        ]);
    }

    #[Route('/add/modifie_bouteille/{id}', name: 'update_bouteille')]
    public function update_bouteille(Bouteille $bouteille, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CaveBouteilleType::class, $bouteille);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bouteille);
            $entityManager->flush();

            return $this->redirectToRoute('app_cave');
        }
        return $this->render('cave/modifie_bouteille.html.twig', [
            'CaveBouteilleType' => $form->createView(),
        ]);
    }

    #[Route('/delete_bouteille/{id}', name: 'delete_bouteille')]
    public function deleteBottle(Bouteille $bouteille, Request $request, EntityManagerInterface $entityManager)
    {
        if ($bouteille->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('app_cave');
        }

        if ($this->isCsrfTokenValid("SUP" . $bouteille->getId(), $request->get('_token'))) {
            $entityManager->remove($bouteille);
            $entityManager->flush();
            return $this->redirectToRoute('app_cave');
        }
    }

    #[Route('/bouteille/{id}', name: 'detail')]
    public function detail(?Bouteille $bouteille): Response
    {
        if (!$bouteille) {
            return $this->redirectToRoute('app_cave');
        }

        $user = $this->getUser();
        $owner = $bouteille->getUser();
        $readonly = $owner !== $user;
        return $this->render('cave/detail.html.twig', [
            'bouteille' => $bouteille,
            'readonly' => $readonly,
            'owner' => $owner,
        ]);
    }
}
