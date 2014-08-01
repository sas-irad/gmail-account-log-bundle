<?php

namespace SAS\IRAD\GmailAccountLogBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * AccountLogRepository
 */
class AccountLogRepository extends EntityRepository {
    
    public function getRecentEntries($limit = 10) {
        
        $qb = $this->getEntityManager()->createQueryBuilder();
        
        $qb->select('entry')
            ->from('GmailAccountLogBundle:AccountLog', 'entry')
            ->orderBy('entry.logTimestamp', 'desc')
            ->setMaxResults($limit);
        
        return $qb->getQuery()->getResult();
    }
    
    
    public function getBySearchTerm($search_term) {
    
        $search_term = strtolower($search_term);
        
        $qb = $this->getEntityManager()->createQueryBuilder();
    
        $qb->select('entry')
            ->from('GmailAccountLogBundle:AccountLog', 'entry')
            ->orderBy('entry.logTimestamp', 'desc');

        if ( preg_match("/^\d{8}$/", $search_term) ) {
            
            $qb->where('entry.pennId = :penn_id')
                ->setParameter(":penn_id", $search_term);
            
        } elseif ( preg_match("/^[a-z][a-z0-9]{1,15}$/", $search_term) ) {

            $qb->where('entry.pennkey = :pennkey')
                ->setParameter(":pennkey", $search_term);
            
        } else {
            return array();
        }
        
        return $qb->getQuery()->getResult();
    }    

    
    public function backFillPennkey($penn_id, $pennkey) {
        
        $qb = $this->getEntityManager()->createQueryBuilder();
        
        $qb->update('GmailAccountLogBundle:AccountLog', 'log')
                ->set('log.pennkey', ':pennkey')
                ->where('log.pennId = :penn_id')
                ->andWhere($qb->expr()->orx(
                    "log.pennkey = ''",
                    "log.pennkey IS NULL"))
                ->setParameter(":pennkey", $pennkey)
                ->setParameter(":penn_id", $penn_id);

        return $qb->getQuery->execute();
    }
    
}
