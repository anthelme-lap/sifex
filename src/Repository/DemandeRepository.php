<?php

namespace App\Repository;

use App\Entity\Demande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Demande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Demande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Demande[]    findAll()
 * @method Demande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Demande::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Demande $entity, bool $flush = true): void
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
    public function remove(Demande $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Demande[] Returns an array of Demande objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Demande
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    // RECUPERATION DU DEPART
    public function findDepart($user)
    {
        $db = $this->getEntityManager()->getConnection();

        $sql ='SELECT depart.namedepart FROM depart INNER JOIN demande ON demande.fkdepart_id=depart.id
               INNER JOIN user ON demande.fkuser_id = user.id WHERE user.id= :user ORDER BY demande.id DESC LIMIT 1
        ';

        $stm = $db->prepare($sql);
        $resut = $stm->executeQuery(['user' =>$user]);
        return $resut->fetchOne();
    }

    // RECUPERATION DU ARRIVE
    public function findArrive($user)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT arrive.namearrive FROM arrive INNER JOIN demande ON demande.fkarrive_id=arrive.id
        INNER JOIN user ON demande.fkuser_id = user.id WHERE user.id= :user ORDER BY demande.id DESC LIMIT 1';

        $stm = $conn->prepare($sql);
        $resut = $stm->executeQuery(['user' =>$user]);
        return $resut->fetchOne();
    }
    // RECUPERATION DE LA DERNIERE DEMANDE
    public function findLastDemandeUser($user)
    {
        $db = $this->getEntityManager()->getConnection();

        $sql = 'SELECT demande.id,demande.created_at,demande.phone,demande.value,demande.fullname,demande.description,
                depart.namedepart,arrive.namearrive FROM demande
                INNER JOIN depart ON demande.fkdepart_id = depart.id 
                INNER JOIN arrive ON demande.fkarrive_id = arrive.id
                INNER JOIN user ON demande.fkuser_id = user.id 
                WHERE user.id= :user ORDER BY demande.id DESC LIMIT 1';

        $stm = $db->prepare($sql);
        $resut = $stm->executeQuery(['user' =>$user]);
        return $resut->fetchAllAssociative();
    }
    // RECUPERATION DE LA DERNIERE DEMANDE
    public function findLastDemandeId($user)
    {
        $db = $this->getEntityManager()->getConnection();

        $sql = 'SELECT demande.id FROM demande
                INNER JOIN depart ON demande.fkdepart_id = depart.id 
                INNER JOIN arrive ON demande.fkarrive_id = arrive.id
                INNER JOIN user ON demande.fkuser_id = user.id 
                WHERE user.id= :user ORDER BY demande.id DESC LIMIT 1';

        $stm = $db->prepare($sql);
        $resut = $stm->executeQuery(['user' =>$user]);
        return $resut->fetch();
    }
}
