<?php

namespace SAS\IRAD\GmailAccountLogBundle\Service;

use Doctrine\ORM\EntityManager;
use SAS\IRAD\GmailAccountLogBundle\Entity\AccountLog;
use Penn\GoogleAdminClientBundle\Service\PersonInfoInterface;

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
    
}