<?php

namespace AppBundle\Entity;

/**
 * ProposalRepository
 *
 */
class ProposalRepository extends \Doctrine\ORM\EntityRepository
{

    private function whereWordsInSection(QueryBuilder $qb, $words) {
        // On 'applatit' la liste des mots avec un join en utilisant le caractère | pour transformer en regex
        $qb
            ->where('p.name LIKE :words')
            ->orWhere('p.dialogue LIKE :words')
            ->orWhere('p.topic LIKE :words')
            ->orWhere('p.objective LIKE :words')
            ->orWhere('p.description LIKE :words')
            ->setParameter('words', '%'.$words.'%');
    }


    public function findArticlesContainingWords($arrayOfWords) {
        $qb = $this->createQueryBuilder('p');

        $this->whereWordsInSection($qb, $arrayOfWords);

        return $qb
                ->getQuery()
                ->getArrayResult();
    }

}
