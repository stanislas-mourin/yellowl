<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

class CategoryController extends AbstractController
{   //Récupérer toutes les catégories
    /**
    * @Route("/categories", name="listCategories", methods={"GET"})
    */
    public function index(CategoriesRepository $categoriesRepo)
    {
        // On récupère la liste des articles
    //$categoriesAll = $categoriesRepo->findAll();
    
    return $this->json($categoriesRepo->findAll(),200,[]);
    
    }

    //Créer une catégorie
    /**
    * @Route("/categories", name="createCategories", methods={"POST"})
    */
    public function storeCat(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator){
        $jsonRecu = $request->getContent();
        try{
        $catCreate=$serializer->deserialize($jsonRecu, Categories::class, 'json');
        
        $errors=$validator->validate($catCreate);
        
        if(count($errors)>0){
            return $this->json($errors, 400);
        }

        $em->persist($catCreate);
        $em->flush();
        
       return $this->json($catCreate, 201, []);
        }catch(NotEncodableValueException $e){

            return $this->json([
                'status' =>400,
                'message'=>$e->getMessage()
            ]);
        }

    }

    //Récupérer une catégorie avec son ID
    /**
    * @Route("/categories/{id}", name="getID_category", methods={"GET"})
    */
    public function readidCat(int $id, CategoriesRepository $categoriesRepo, ValidatorInterface $validator){
        $readIdCat = $categoriesRepo->find($id);
      
        //$errors=$validator->validate($readIdCat);
        if(empty($readIdCat)){
            return new JsonResponse(['message'=>'Category not found'], Response::HTTP_NOT_FOUND);
        }
        //if(count($errors)>0){
            // return $this->json($errors, 400);
        // }
        return $this->json($readIdCat, 200, []);
        }


    //MAJ d'une catégorie avec son ID
    /**
    * @Route("/categories/{id}", name="update_category", methods={"PUT"})
    */
    public function updateCat(int $id,Request $request, SerializerInterface $serializer, EntityManagerInterface $em, CategoriesRepository $categoriesRepo){

        $cat= ($categoriesRepo->find($id));

        if(empty($cat)){
            return new JsonResponse(['message'=>'Category not found'], Response::HTTP_NOT_FOUND);
        }

        $donnees = json_decode($request->getContent(),true);
    
        $cat->setNamecategory($donnees["namecategory"]);
        $cat->setAvatar($donnees["avatar"]);

        $em->persist($cat);
        $em->flush();

        return $this->json($cat, 201, []);
        
        
    }

    //Supprimer une catégorie avec son ID
    /**
    * @Route("/categories/{id}", name="delete_category", methods={"DELETE"})
    */
    public function deleteCat($id, EntityManagerInterface $em, CategoriesRepository $categoriesRepo){

        $cat= ($categoriesRepo->find($id));
        if(empty($cat)){
            return new JsonResponse(['message'=>'Category not found'], Response::HTTP_NOT_FOUND);
        }
        $em->remove($cat);
        $em->flush();

        return $this->json($cat, 201, []);
        
    }
   
}

    