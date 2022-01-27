<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\HiddenRepository;
use App\Entity\Posts;
use App\Entity\Hidden;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

header("Access-Control-Allow-Origin: *");
class HiddenController extends AbstractController
{
    #[Route('/hidden/user/{idUser}', name: 'hiddenUser', methods:['GET'])]
    public function hiddenUser(int $idUser, HiddenRepository $hiddenRepository)
    {
        return $this->json($hiddenRepository->findBy(['idUser' => $idUser], []));
    }


}
