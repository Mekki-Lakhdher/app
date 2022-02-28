<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\LogFileLoader;
use App\Service\LogFileReader;
use App\Service\LogFileSaver;
use App\Service\LogFileLineValidator;
use \DateTime;

class ManageLogCommand extends Command
{
    protected static $defaultName = 'app:manage_log';

    private $log_file_loader;

    private $log_file_reader;

    private $log_file_saver;

    public function __construct(
        LogFileLoader $log_file_loader,
        LogFileReader $log_file_reader,
        LogFileSaver $log_file_saver,
        LogFileLineValidator $log_file_line_validator
    )
    {
        $this->log_file_loader=$log_file_loader;
        $this->log_file_reader=$log_file_reader;
        $this->log_file_saver=$log_file_saver;
        $this->log_file_line_validator=$log_file_line_validator;
        parent::__construct();
    }

    protected function configure(): void
    {
        // ...
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {   
        // load the file
        $fp = $this->log_file_loader->loadLogFile();

        // continuously listen to the file changes
        while (true) {
            // recheck the file every 100 ms to read any new line
            $new_line=$this->log_file_reader->readNewLine($fp);
            $new_line=str_replace(array("\n", "\r"), '', $new_line);
            if ($new_line === false) {
                usleep(100000);
                continue;
            }
            // save validated line to database
            if($line_data = $this->log_file_line_validator->validateLine($new_line))
            {
                // unset($_ENV['DATABASE_URL']);
                // $_ENV['DATABASE_URL']="mysql://app:apppass@127.0.0.1:8982/dev?serverVersion=5.7.22";
                $this->log_file_saver->saveNewLine($line_data);
                echo "SAVED | ".$new_line."\n";
            }
        }

        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID
    }
}