<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FavoritesRepository;
use App\Entity\Posts;
use App\Entity\Favorites;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

header("Access-Control-Allow-Origin: *");
class FavoriteController extends AbstractController
{
    #[Route('/favorites/{idUser}', name: 'favoritesOneUser', methods:['GET'])]
    public function favoritesOneUser(int $idUser, FavoritesRepository $favoritesRepository)
    {
        return $this->json($favoritesRepository->favoritesOneUser($idUser), 200,[], []);
    }

    #[Route('/favorites', name: 'createFavorite', methods:['POST'])]
    public function createFavorite(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        try {
            $jsonReceived = $request->getContent();
            $favorite = $serializer->deserialize($jsonReceived, Favorites::class, 'json');

            $errors = $validator->validate($favorite);

            if (count($errors) >0){
                return $this->json($errors,400);
            }

            $em->persist($favorite);
            $em->flush();
            return $this->json($favorite,201,[], []);
        } 
        catch (NotEncodableValueException $e){
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ],400);
        }

    }


    #[Route('/favorites/{idUser}/{idPost}', name: 'deleteFavorite', methods:['DELETE'])]
    public function deleteFavorite(FavoritesRepository $favoritesRepository, int $idUser, int $idPost, EntityManagerInterface $em)
    {
        try{
            $favorite = $favoritesRepository->findBy(['idUser' => $idUser, 'idPost' => $idPost], []);
            $todelete = $favorite[0];
            $em->remove($todelete);
            $em->flush();
            return $this->json([
                'status' => 202,
                'message' => 'Combination of idUser ' . $idUser . ' and idPost ' . $idPost . ' has been deleted from favorites.'
            ],202);
        } catch (\Exception $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ],400);
        }
    }


}
