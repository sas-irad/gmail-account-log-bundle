<?php
/**
 * This service spawns off instances of AccountLogger as requested
 * @author robertom@sas.upenn.edu
 */

namespace SAS\IRAD\GmailAccountLogBundle\Service;

use Doctrine\ORM\EntityManager;
use SAS\IRAD\GmailAccountLogBundle\Logger\AccountLogger;
use SAS\IRAD\PersonInfoBundle\PersonInfo\PersonInfoInterface;


class AccountLoggerService {
    
    private $em;
    private $params;
    private $timezone;
    
    public function __construct(EntityManager $em, $params) {
        $this->em = $em;
        $this->params = $params;
    }

    public function init(PersonInfoInterface $personInfo) {
        return new AccountLogger($this->em, $personInfo, $this->params);
    }
 
}