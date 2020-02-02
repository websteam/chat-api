<?php

namespace App\Repository;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

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
     * @return User Returns user on login or creates new
     */
    public function loginOrCreate($email): User
    {
        $user = $this->findOneBy([
            'email' => $email
        ]);

        if (is_null($user)) {
            $user = $this->create(
                $email
            );
        } else {
            $user->setLastLogin(new DateTime());

            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();
        }

        return $user;
    }

    public function create($email): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setName($email);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        return $user;
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

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
