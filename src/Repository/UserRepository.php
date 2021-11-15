<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Boolean;

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
     * @return User[]
     */
    public function findAllLastNameLongerThan(int $numChars): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT u
             FROM App\Entity\User u
             WHERE length(u.lastName) > :numChars
             ORDER BY u.id ASC'
        )->setParameter('numChars', $numChars);

        return $query->getResult();
    }

    /**
     * @return User[]
     */
    public function findAllByRole(string $role, bool $isSortedAsc) : array
    {
        $qb = $this->createQueryBuilder('u')
                ->where('u.role = :role')
                ->setParameter('role', $role);

        // add this clause dynamically
        ($isSortedAsc) ? $qb->orderBy('u.id', 'ASC') : $qb->orderBy('u.id', 'DESC');
            
        $query = $qb->getQuery();
        return $query->execute();
    }
}
