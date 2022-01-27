<?php

namespace App\Controller;

use App\Repository\CommentsRepository;
use App\Entity\Comments;
use Normalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

class CommentController extends AbstractController
{
    
    #[Route('/comments', name: 'listComments', methods:['GET'])]
    public function listComments(CommentsRepository $commentsRepository): Response
    {
        return $this->json($commentsRepository->findAll(),200,[], []);
    }


    #[Route('/comments', name: 'createComments', methods:['POST'])]
    public function createComments(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator): Response
    {
        try {
            $jsonReceived = $request->getContent();
            $comment = $serializer->deserialize($jsonReceived, Comments::class, 'json');
            $comment->setDateOfComment (new \DateTime());
            $comment->setLikes (0);
            $comment->setDislikes (0);

            $errors = $validator->validate($comment);

            if (count($errors) >0){
                return $this->json($errors,400);
            }

            $em->persist($comment);
            $em->flush();
            return $this->json($comment,201,[], ['groups' => 'comments:read']);
        } 
        catch (NotEncodableValueException $e){
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ],400);
        }

    }


    #[Route('/comments/{id}', name: 'oneComment', methods:['GET'])]
    public function oneComment(CommentsRepository $commentsRepository, int $id): Response
    {
        return $this->json($commentsRepository->find($id),200,[], ['groups' => 'comments:read']);
    }


    #[Route('/comments/content/{id}', name: 'updateContentOneComment', methods:['PUT'])]
    public function updateContentOneComment(CommentsRepository $commentsRepository, int $id, Request $request, EntityManagerInterface $em, ValidatorInterface $validator): Response
    {
        $comment = $commentsRepository->find($id);

        if (empty($comment)) {
            return $this->json([
                'status' => 404,
                'message' => "Comment not found"
            ],404);
        }

        $jsonReceived = json_decode($request->getContent(), true);
        $newContent = $jsonReceived['content'];
        $comment->setContent($newContent);
        
        $errors = $validator->validate($comment);
            if (count($errors) >0){
                return $this->json($errors,400);
            }

        $em->persist($comment);
        $em->flush();
        return $this->json($comment,201,[], ['groups' => 'comments:read']);
    }


    #[Route('/comments/like/{id}', name: 'updateLikeOneComment', methods:['PUT'])]
    public function updateLikeOneComment(CommentsRepository $commentsRepository, int $id, EntityManagerInterface $em): Response
    {
        $comment = $commentsRepository->find($id);

        if (empty($comment)) {
            return $this->json([
                'status' => 404,
                'message' => "Comment not found"
            ],404);
        }

        $comment->setLikes($comment->getLikes()+1);
        $em->persist($comment);
        $em->flush();
        return $this->json($comment,201,[], ['groups' => 'comments:read']);
    }


    #[Route('/comments/dislike/{id}', name: 'updateDislikeOneComment', methods:['PUT'])]
    public function updateDislikeOneComment(CommentsRepository $commentsRepository, int $id, EntityManagerInterface $em): Response
    {
        $comment = $commentsRepository->find($id);

        if (empty($comment)) {
            return $this->json([
                'status' => 404,
                'message' => "Comment not found"
            ],404);
        }

        $comment->setDislikes($comment->getDislikes()+1);
        $em->persist($comment);
        $em->flush();
        return $this->json($comment,201,[], ['groups' => 'comments:read']);
    }


    #[Route('/comments/{id}', name: 'deleteOneComment', methods:['DELETE'])]
    public function deleteComment(CommentsRepository $commentsRepository, int $id, EntityManagerInterface $em): Response
    {
        try{
            $comment = $commentsRepository->find($id);
            $em->remove($comment);
            $em->flush();
            return $this->json([
                'status' => 202,
                'message' => 'Comment with id ' . $id . ' has been deleted.'
            ],202);
        } catch (\Exception $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ],400);
        }
    }


    #[Route('/comments/post/{idPost}', name: 'commentsOnePost', methods:['GET'])]
    public function commentsOnePost(CommentsRepository $commentsRepository, int $idPost): Response
    {
        return $this->json($commentsRepository->commentsOnePost($idPost),200,[], ['groups' => 'comments:read']);
    }


    #[Route('/comments/post/{idPost}', name: 'deleteCommentsOnePost', methods:['DELETE'])]
    public function deleteCommentsOnePost(CommentsRepository $commentsRepository, int $idPost, EntityManagerInterface $em): Response
    {
        $arraydef = [];
        $arrayinit = $commentsRepository->getIdCommentsOnePost($idPost);
        foreach ($arrayinit as $arr) {
            array_push($arraydef, $arr['id']);
            try{
                $comment = $commentsRepository->find($arr['id']);
                $em->remove($comment);
                $em->flush();
            } catch (\Exception $e) {
                return $this->json([
                    'status' => 400,
                    'id with error' => $arr['id'],
                    'message' => $e->getMessage()
                ],400);
            }
        }
        return $this->json($arraydef,202);
    }

    #[Route('/comments/user/{idUser}', name: 'getCommentsOneUser', methods:['GET'])]
    public function getCommentsOneUser(CommentsRepository $commentsRepository, int $idUser): Response
    {
        return $this->json($commentsRepository->findBy(
            ['idUser' => $idUser],
            ['id' => 'DESC']
        ));
    }

    #[Route('/comments/user/{idUser}', name: 'deleteCommentsOneUser', methods:['DELETE'])]
    public function deleteCommentsOneUser(CommentsRepository $commentsRepository, int $idUser, EntityManagerInterface $em): Response
    {
        $arraydef = [];
        $arrayinit = $commentsRepository->getIdCommentsOneUser($idUser);
        foreach ($arrayinit as $arr) {
            array_push($arraydef, $arr['id']);
            try{
                $comment = $commentsRepository->find($arr['id']);
                $em->remove($comment);
                $em->flush();
            } catch (\Exception $e) {
                return $this->json([
                    'status' => 400,
                    'id with error' => $arr['id'],
                    'message' => $e->getMessage()
                ],400);
            }
        }
        return $this->json($arraydef,202);
    }


    #[Route('/comments/number/post/{idPost}', name: 'numberCommentsOnePost', methods:['GET'])]
    public function numberCommentsOnePost(CommentsRepository $commentsRepository, int $idPost): Response
    {
        return $this->json($commentsRepository->numberCommentsOnePost($idPost),200,[], ['groups' => 'comments:read']);
    }

}
