<?php

namespace App\Repository;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Ahc\Jwt\JWT;

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

        if (empty($user->getRefreshToken())) {
            $this->refreshToken($user);
        }

        $jwt = new JWT($user->getRefreshToken(), 'HS256', 3600, 10);

        $user->setToken($jwt->encode([
            'uid'    => $user->getId(),
            'scopes' => ['user'],
        ]));

        return $user;
    }

    public function create($email): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setName($email);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        $this->refreshToken($user);

        return $user;
    }

    public function refreshToken(User $user): User
    {
        $user->setRefreshToken($this->_createRefreshToken($user));

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        return $user;
    }

    private function _createRefreshToken(User $user): string
    {
        $jwt = new JWT($_ENV['APP_SECRET'], 'HS256');

        return $jwt->encode([
            'uid'    => $user->getId(),
            'scopes' => ['user'],
        ]);
    }
}
