<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Repository\PostsRepository;
use App\Repository\FavoritesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

header("Access-Control-Allow-Origin: *");
class PostController extends AbstractController
{
    #[Route('/posts', name: 'listPosts', methods: ['GET'])]
    public function listPosts(PostsRepository $postsRepository)
    {
        // return $this->json($postsRepository->findAll(), 200, [], ['groups' => 'posts:read']);
        return $this->json($postsRepository->findBy([], ['id' => 'DESC']), 200, [], ['groups' => 'posts:read']);
    }


    #[Route('/posts', name: 'createPosts', methods: ['POST'])]
    public function createPosts(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        try {
            $jsonReceived = $request->getContent();
            $post = $serializer->deserialize($jsonReceived, Posts::class, 'json');
            $post->setDateOfPost(new \DateTime());
            $post->setLikes(0);
            $post->setDislikes(0);
            $post->setReported(0);

            $errors = $validator->validate($post);

            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }

            $em->persist($post);
            $em->flush();
            return $this->json($post, 201, [], ['groups' => 'posts:read']);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }


    #[Route('/posts/{id}', name: 'onePosts', methods: ['GET'], requirements: ["id" => "\d+"])]
    public function onePosts(PostsRepository $postsRepository, int $id)
    {
        // $this->headers->set('Access-Control-Allow-Origin', '*');
        return $this->json($postsRepository->find($id), 200, [], ['groups' => 'posts:read']);;
    }


    #[Route('/posts/{id}', name: 'deleteOnePost', methods: ['DELETE'])]
    public function deleteOnePost(PostsRepository $postsRepository, int $id, EntityManagerInterface $em)
    {
        try {
            $post = $postsRepository->find($id);
            $em->remove($post);
            $em->flush();
            return $this->json([
                'status' => 202,
                'message' => 'Post with id ' . $id . ' has been deleted.'
            ], 202);
        } catch (\Exception $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }


    #[Route('/posts/user/{idUser}', name: 'postsOneUser', methods: ['GET'])]
    public function postsOneUser(PostsRepository $postsRepository, int $idUser)
    {
        return $this->json($postsRepository->findBy(['idUser' => $idUser], ['dateOfPost' => 'DESC']), 200, [], ['groups' => 'posts:read']);
    }


    #[Route('/posts/search/{searchText}', name: 'postsPerSearch', methods: ['GET'])]
    public function postsPerSearch(PostsRepository $postsRepository, string $searchText)
    {
        $result = $postsRepository->searchTitle($searchText);
        return $this->json($result, 200, [], ['groups' => 'posts:read']);
    }


    #[Route('/posts/category/{idCategory}', name: 'postsOneCategory', methods: ['GET'])]
    public function postsOneCategory(PostsRepository $postsRepository, int $idCategory)
    {
        return $this->json($postsRepository->findBy(['idCategory' => $idCategory], ['dateOfPost' => 'DESC']), 200, [], ['groups' => 'posts:read']);
    }


    #[Route('/posts/likes/{id}', name: 'numberLikesPost', methods: ['GET'])]
    public function numberLikesPost(PostsRepository $postsRepository, int $id)
    {
        return $this->json($postsRepository->find($id), 200, [], ['groups' => 'post:like']);
    }


    #[Route('/posts/dislikes/{id}', name: 'numberDislikesPost', methods: ['GET'])]
    public function numberDislikesPost(PostsRepository $postsRepository, int $id)
    {
        return $this->json($postsRepository->find($id), 200, [], ['groups' => 'post:dislike']);
    }


    #[Route('/posts/likesStat', name: 'likesPerPost', methods: ['GET'])]
    public function likesPerPost(PostsRepository $postsRepository)
    {
        $result = $postsRepository->numberLikesPerPost();
        return $this->json($result, 200, [], []);
    }


    #[Route('/posts/dislikesStat', name: 'dislikesPerPost', methods: ['GET'])]
    public function dislikesPerPost(PostsRepository $postsRepository)
    {
        $result = $postsRepository->numberDislikesPerPost();
        return $this->json($result, 200, [], []);
    }


    #[Route('/posts/date/{id}', name: 'datePost', methods: ['GET'])]
    public function datePost(PostsRepository $postsRepository, int $id)
    {
        return $this->json($postsRepository->find($id), 200, [], ['groups' => 'post:date']);
    }


    #[Route('/posts/perDay', name: 'postPerDay', methods: ['GET'])]
    public function postPerDay(PostsRepository $postsRepository)
    {
        $result = $postsRepository->numberPostPerDay();
        $resultTransformed = [];
        foreach ($result as $day) {
            if (array_key_exists(date_format($day["dateOfPost"], 'Y-m-d'), $resultTransformed)) {
                $resultTransformed[date_format($day["dateOfPost"], 'Y-m-d')] = $day['1'] + $resultTransformed[date_format($day["dateOfPost"], 'Y-m-d')];
            } else {
                $resultTransformed[date_format($day["dateOfPost"], 'Y-m-d')] = $day['1'];
            }
        };
        return $this->json($resultTransformed, 200, [], []);
    }


    #[Route('/posts/like/{id}', name: 'updateLikeOnePost', methods: ['PUT'])]
    public function updateLikeOnePost(PostsRepository $postsRepository, FavoritesRepository $favoritesRepository, int $id, EntityManagerInterface $em)
    {
        $post = $postsRepository->find($id);

        if (empty($post)) {
            return $this->json([
                'status' => 404,
                'message' => "Post not found"
            ], 404);
        }

        // $favorites = $favoritesRepository->findOneBy([
        //     'idUser' => $post->getIdUser(),
        //     'idPost' => $id,
        // ]);

        // if (empty($favorites)) {

        //     return $this->json([
        //         'status' => 404,
        //         'message' => "Favorites not found"
        //     ], 404);
        // }
        

        $post->setLikes($post->getLikes() + 1);
        $em->persist($post);
        $em->flush();
        return $this->json($post, 201, [], ['groups' => 'posts:read']);
    }


    #[Route('/posts/dislike/{id}', name: 'updateDislikeOnePost', methods: ['PUT'])]
    public function updateDislikeOnePost(PostsRepository $postsRepository, int $id, EntityManagerInterface $em)
    {
        $post = $postsRepository->find($id);

        if (empty($post)) {
            return $this->json([
                'status' => 404,
                'message' => "Post not found"
            ], 404);
        }

        $post->setDislikes($post->getDislikes() + 1);
        $em->persist($post);
        $em->flush();
        return $this->json($post, 201, [], ['groups' => 'posts:read']);
    }


    #[Route('/posts/report/{id}', name: 'updateReportOnePost', methods: ['PUT'])]
    public function updateReportOnePost(PostsRepository $postsRepository, int $id, EntityManagerInterface $em): Response
    {
        $post = $postsRepository->find($id);

        if (empty($post)) {
            return $this->json([
                'status' => 404,
                'message' => "Post not found"
            ], 404);
        }

        $post->setReported($post->getReported() + 1);
        $em->persist($post);
        $em->flush();
        return $this->json($post, 201, [], ['groups' => 'posts:read']);
    }


    #[Route('/posts/userNickname/{id}', name: 'userNicknamePost', methods: ['GET'])]
    public function userNicknamePost(PostsRepository $postsRepository, int $id)
    {
        try {
            return $this->json($postsRepository->nicknameUserOfPost($id), 200, [], []);
        } catch (\Exception $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }


    #[Route('/posts/categoryName/{id}', name: 'categoryNameOfPost', methods: ['GET'])]
    public function categoryNameOfPost(PostsRepository $postsRepository, int $id)
    {
        try {
            return $this->json($postsRepository->categoryNameOfPost($id), 200, [], []);
        } catch (\Exception $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }


    #[Route('/posts/nbByCategory', name: 'numberPostByCategory', methods: ['GET'])]
    public function numberPostByCategory(PostsRepository $postsRepository)
    {
        try {
            return $this->json($postsRepository->numberPostbyCategory(), 200, [], []);
        } catch (\Exception $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }


    #[Route('/posts/likesByCategory', name: 'numberLikesByCategory', methods: ['GET'])]
    public function numberLikesByCategory(PostsRepository $postsRepository)
    {
        try {
            return $this->json($postsRepository->numberLikesbyCategory(), 200, [], []);
        } catch (\Exception $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }

}
