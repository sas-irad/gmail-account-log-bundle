<?php

namespace SAS\IRAD\GmailAccountLogBundle\Logger;

use Doctrine\ORM\EntityManager;
use SAS\IRAD\GmailAccountLogBundle\Entity\AccountLog;
use SAS\IRAD\PersonInfoBundle\PersonInfo\PersonInfoInterface;


class AccountLogger {

    private $personInfo;
    private $em;
    private $params;
    private $timezone;

    public function __construct(EntityManager $em, PersonInfoInterface $personInfo, $params) {
        $this->em = $em;
        $this->params = $params;
        $this->personInfo = $personInfo;

        if ( !$personInfo->getPennId() ) {
            throw new \Exception("AccountLogger::__construct requires a penn_id value defined in the PersonInfo object");
        }

        $this->timezone = new \DateTimeZone($params['timezone']);
    }

    public function log($log_type, $message) {

        $entry = new AccountLog();

        $entry->setLogTimestamp(new \DateTime("now", $this->timezone));
        $entry->setPennkey($this->personInfo->getPennkey());
        $entry->setPennId($this->personInfo->getPennId());
        $entry->setLogType($log_type);
        $entry->setMessage($message);

        $this->em->persist($entry);
        $this->em->flush();
    }


    public function backFillPennkey() {

        $penn_id = $this->personInfo->getPennId();
        $pennkey = $this->personInfo->getPennkey();

        if ( !$pennkey ) {
            throw new \Exception("AccountLogger::backFillPennkey requires a pennkey value in the PersonInfo object");
        }

        $repo = $this->em->getRepository("GmailAccountLogBundle:AccountLog");
        return $repo->backFillPennkey($penn_id, $pennkey);
    }
}