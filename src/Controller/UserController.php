<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $user= new User();
        $user->setUsername('nomeUtilisator');
        $user->setEmail('emailo@falso.com');
        $user->setPassword('P455aW0rDo');

        $message = new Message();
        $message->setTitle('Lo titolo del messagio de presentation');
        $message->setContent('Le contenu du message de présentation');

        $user->addMessage($message);


        $entityManager = $doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->render('test.html.twig',[
            'controller_name'=>'UserController'
       ]);
    }
//    #[Route('/find-user', name:'findUser')]
//    public  function findUser(UserRepository $userRepository): Response
//    {
//        dump($userRepository->findAll());
//
//        dump($userRepository->findBy([
//            'email' => 'email@fake.com',
//            'username' => 'nomUtilisateur'
//        ]));
//
//        dump($userRepository->findByEmail('email@falso.com'));
//        dump($userRepository->find(1));
//        return new Response('<body>wesh</body>');
//    }

    #[Route('/find-user')]
    public function findUser(UserRepository $userRepository): Response
    {
        $user = $userRepository->findUserWithQueryBuilder('nomeUtilisator');

        if (!$user){
            throw $this->createNotFoundException('Unknown User');
        }
        dump($user->getMessages()->toArray());
        return new Response(sprintf('<body>%s</body>', $user->getEmail()));
    }
}
