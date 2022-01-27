<?php

namespace App\Controller;

use Normalizer;
use App\Entity\Users;
use App\Controller\APIController;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
// header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

class APIController extends AbstractController
{

    #[Route('/users', name:'getusers', methods:['GET'])]
    public function getUsers(UsersRepository $usersRepo)
    {
        return $this->json($usersRepo->findAll(), 200, [], ["groups"=>"users:read"]);
    }

    #[Route('/users', name:'postusers', methods:['POST'])]
    public function postUser(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $data = $request->getcontent();

        try {
            $newuser = $serializer->deserialize($data, Users::class, 'json');
            $errors = $validator->validate($newuser);
            if(count($errors) > 0) {
                return $this->json($errors, 400);
            }

            $em->persist($newuser);
            $em->flush();
            return $this->json($newuser, 201, [], ["groups"=>"users:read"]);
            
        } catch(NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
      
    }   
 
    
    #[Route('/users/{id}', name:'getuserdata', methods:['GET'])]
    public function getUserId(int $id, UsersRepository $usersRepo)
    {   
        $existingUser = $usersRepo->find($id);
        if (empty($existingUser)) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        else{
            return $this->json($existingUser, 200, [], ["groups"=>"users:read"]);
        }        
    }

    #[Route('/users/{id}', name:'deleteuserdata', methods:['DELETE'])]
    public function deleteUserId(int $id, UsersRepository $usersRepo, EntityManagerInterface $em)
    {  
        $existingUser = $usersRepo->find($id);
        if (empty($existingUser)) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        else {
            $em->remove($existingUser);
             $em->flush();
             return new JsonResponse(['status' => 'User deleted'], Response::HTTP_NO_CONTENT);
        }
    }

    #[Route('/users/{id}', name:'putuserdata', methods:['PUT'])]
    public function putUserId(int $id, Request $request,  UsersRepository $usersRepo, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $data = json_decode($request->getContent(), true);
        
        $existingUser = $usersRepo->find($id);
        if (empty($existingUser)) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        else {
            try{
                $existingUser->setLastName($data['lastName']);
                $existingUser->setFirstName($data['firstName']);
                $existingUser->setNickName($data['nickName']);
                $existingUser->setEmailAddress($data['emailAddress']);
                $date = new \DateTime($data['dateOfBirth']);
                $existingUser->setDateOfBirth($date);
                // $existingUser->setPassword($data['password']);
                $existingUser->setIsAdmin($data['isAdmin']);
                $existingUser->setPreferences($data['preferences']);
                // $existingUser->setAvatar($data['avatar']);
    
                $em->persist($existingUser);
                $em->flush();

                return $this->json($existingUser, 200, [], ["groups"=>"users:read"]);
            } catch(\Exception $e) {
                return $this->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ], 400);
            }
        }   
    }
    #[Route('/users/password/{id}', name:'getuserpassword', methods:['GET'])]
    public function getUserPassword(int $id, UsersRepository $usersRepo)
    {   
        $existingUser = $usersRepo->find($id);
        if (empty($existingUser)) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        else{
            return $this->json($existingUser->getPassword(), 200, [], ["groups"=>"users:read"]);
        }        
    }
    #[Route('/users/password/{id}', name:'putuserpassword', methods:['PUT'])]
    public function putUserPassword(int $id, Request $request, UsersRepository $usersRepo)
    {   
        $data = json_decode($request->getContent(), true);

        $existingUser = $usersRepo->find($id);
        if (empty($existingUser)) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        else{
            try {
                $existingUser->setPassword($data['password']);
                return $this->json($existingUser->getPassword(), 200, [], ["groups"=>"users:read"]);
            } catch(\Exception $e) {
                return $this->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ], 400);
            }
        }        
    }
    #[Route('/users/avatar/{id}', name:'getuseravatar', methods:['GET'])]
    public function getUserAvatar(int $id, UsersRepository $usersRepo)
    {   
        $existingUser = $usersRepo->find($id);
        if (empty($existingUser)) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        else{
            $avatar = $existingUser->getAvatar();
            $filename = $this->getParameter('kernel.project_dir') . '/public/assets/' . $avatar;
                if (file_exists($filename)) {
                    return new BinaryFileResponse($filename);
                } else {
                    return new JsonResponse(['message' => 'Avatar file not found', 'file' => $avatar], 404);
                }
        }  
    }
    #[Route('/users/avatar/{id}', name:'putuseravatar', methods:['PUT'])]
    public function putUserAvatar(int $id, Request $request, EntityManagerInterface $em, UsersRepository $usersRepo)
    {   
        $data = json_decode($request->getContent(), true);

        $existingUser = $usersRepo->find($id);
        $avatar = $existingUser->getAvatar();
        if (empty($existingUser)) {
            return new JsonResponse(['message' => 'User not found', 'file' => $avatar], Response::HTTP_NOT_FOUND);
        }
        else{
            try {
                $existingUser->setAvatar($data['avatar']);
                $em->persist($existingUser);
                $em->flush();
                return $this->json($existingUser->getAvatar(), 200, [], ["groups"=>"users:read"]);
            } catch(\Exception $e) {
                return $this->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ], 400);
            }
        }     
    }
}
