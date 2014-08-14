<?php

namespace AccountLoggerTestBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use SAS\IRAD\PersonInfoBundle\PersonInfo\PersonInfo;

class AddLogEntryCommand extends ContainerAwareCommand {
    
    protected function configure() {
        
        $this
            ->setName('log-test:add-entry')
            ->setDescription('Create a google account for the supplied pennkey / penn id')
            ->addOption('penn-id',  null, InputOption::VALUE_REQUIRED, "The user's Penn ID")
            ->addOption('pennkey',  null, InputOption::VALUE_REQUIRED, "The user's Pennkey")
            ->addOption('message',  null, InputOption::VALUE_REQUIRED, "The user's last name")
            ;
        
        $this->setHelp("Create a test entry in the account log.");
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        
        $personInfo = $this->getUserInput($input);
        $message    = $input->getOption('message');

        // okay we have a valid person and their info. get the gmail admin service
        $logger = $this->getContainer()->get('account_logger_service')->init($personInfo);
        $logger->log('INFO', $message);
        $output->writeln("Account log entry created for: " . $personInfo->getPennkey());
    }
    
    
    /**
     * Decode user input and return a PersonInfo object
     * @param InputInterface $input
     * @throws \Exception
     * @return PersonInfo
     */
    public function getUserInput(InputInterface $input) {
        
        // get user input
        $penn_id    = $input->getOption('penn-id');
        $pennkey    = strtolower($input->getOption('pennkey'));
        
        // validate penn_id and pennkey input
        if ( $penn_id && !preg_match("/^\d{8}$/", $penn_id) ) {
            throw new \Exception("The penn-id parameter \"$penn_id\" is incorrect.");
        }

        if ( $pennkey && !preg_match("/^[a-z][a-z0-9]{1,15}$/", $pennkey) ) {
            throw new \Exception("The pennkey parameter \"$pennkey\" is incorrect.");
        }

        // we need a pennkey or penn_id for lookups
        if ( !$penn_id && !$pennkey ) {
            throw new \Exception("A valid penn-id or pennkey parameter must be specified to create log entry.");
        }
        
        $options = compact('penn_id', 'pennkey');
        return new PersonInfo($options);
    }
   
}