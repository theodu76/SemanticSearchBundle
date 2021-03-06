<?php

namespace AppBundle\Entity;

/**
 * ProposalRepository
 *
 */
class ProposalRepository extends \Doctrine\ORM\EntityRepository
{

    public function whereWordsInSection(QueryBuilder $qb, $words) {
        // On 'applatit' la liste des mots avec un join en utilisant le caractère | pour transformer en regex
        $qb
            ->where('p.name LIKE :words')
            ->orWhere('p.dialogue LIKE :words')
            ->orWhere('p.topic LIKE :words')
            ->orWhere('p.objective LIKE :words')
            ->orWhere('p.description LIKE :words')
            ->setParameter('words', '%'.$words.'%');
    }


    public function findArticles($arrayOfWords) {
        $qb = $this->createQueryBuilder('p');

        return $qb
                ->getQuery()
                ->getArrayResult();
    }

}
