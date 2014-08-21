<?php

namespace AccountLoggerTestBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use SAS\IRAD\PersonInfoBundle\PersonInfo\PersonInfo;

class UpdatePennkeyCommand extends ContainerAwareCommand {
    
    protected function configure() {
        
        $this
            ->setName('log-test:update-pennkey')
            ->setDescription("Update account logs with a user's new pennkey.")
            ->addOption('penn-id',     null, InputOption::VALUE_REQUIRED, "The user's Penn ID")
            ->addOption('old-pennkey', null, InputOption::VALUE_REQUIRED, "The user's old Pennkey")
            ->addOption('new-pennkey', null, InputOption::VALUE_REQUIRED, "The user's new Pennkey")
            ;
        
        $this->setHelp("Update account logs with a user's new pennkey");
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        
        $penn_id     = $input->getOption('penn-id');
        $old_pennkey = $input->getOption('old-pennkey');
        $new_pennkey = $input->getOption('new-pennkey');

        // okay we have a valid person and their info. get the gmail admin service
        $logger = $this->getContainer()->get('account_logger');
        $logger->updatePennkey($penn_id, $old_pennkey, $new_pennkey);
        $output->writeln("Account logs updated for: $penn_id");
    }

   
}