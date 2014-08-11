<?php

namespace SAS\IRAD\GmailAccountLogBundle\Service;

use Doctrine\ORM\EntityManager;
use SAS\IRAD\GmailAccountLogBundle\Entity\AccountLog;
use SAS\IRAD\PersonInfoBundle\PersonInfo\PersonInfoInterface;


class AccountLogger {
    
    private $em;
    private $params;
    private $timezone;
    
    public function __construct(EntityManager $em, $params) {
        $this->em = $em;
        $this->params = $params;
        
        
        $this->timezone = new \DateTimeZone($params['timezone']);
    }
    
    public function log(PersonInfoInterface $personInfo, $log_type, $message) {
        
        $entry = new AccountLog();
        
        $entry->setLogTimestamp(new \DateTime("now", $this->timezone));
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
        
        $repo = $this->em->getRepository("GmailAccountLogBundle:AccountLog");
        return $repo->backFillPennkey($penn_id, $pennkey);
    } 
}