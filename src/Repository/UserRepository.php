<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(User $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(User $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findUserWithQueryBuilder(string $username): ?User
    {
        return $this->createQueryBuilder('u')
            ->select('u, m')
            ->leftJoin('u.messages', 'm')
            ->andWhere('u.username= :username')
            ->setParameter('username', $username)
            ->orderBy('u.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
// ci-dessous d'autres example du cours :
    /*public function findUserWithQueryBuilder(string $username): ?user
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.username= :username')
            ->setParameter('username', $username)
            ->orderBy('u.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

// en ajoutant des conditions au fur et a mesure :

    public function findUserWithQueryBuilder(string $username, string $email = null, bool $sortAsc = true): ?user
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->andWhere('u.username= :username')
            ->setParameter('username', $username);

        if ($email){
            $queryBuilder->andWhere('u.email = :email')
                ->setParameter('email',$email);
        }

        return $queryBuilder
            ->orderBy('u.id', $sortAsc ?'ASC':'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    */

// ci-dessous des méthodes natives :
    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
