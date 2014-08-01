<?php

namespace SAS\IRAD\GmailAccountLogBundle\Service;

use Doctrine\ORM\EntityManager;
use SAS\IRAD\GmailAccountLogBundle\Entity\AccountLog;
use SAS\IRAD\GoogleAdminClientBundle\Service\PersonInfoInterface;


class AccountLogger {
    
    private $em;
    
    public function __construct(EntityManager $em) {
        $this->em = $em;
    }
    
    public function log(PersonInfoInterface $personInfo, $log_type, $message) {
        
        $entry = new AccountLog();
        
        $entry->setLogTimestamp(new \DateTime());
        $entry->setPennkey($personInfo->getPennkey());
        $entry->setPennId($personInfo->getPennId());
        $entry->setLogType($log_type);
        $entry->setMessage($message);
        
        $this->em->persist($entry);
        $this->em->flush();
        
    }
    
    
    public function backFillPennkey(PersonInfoInterface $personInfo) {
        
        $penn_id = $personInfo->getPennId();
        $pennkey = $personInfo->getPennkey();
        
        if ( !$penn_id || !$pennkey ) {
            throw new \Exception("PersonInfo must contain penn_id and pennkey for backFillPennkey operation");
        }
        
        $repo = $this->em->getRepository("AccountLogRepository:AccountLog");
        return $repo->backFillPennkey($penn_id, $pennkey);
    } 
}