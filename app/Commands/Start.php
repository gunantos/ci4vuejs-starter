<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class Start extends BaseCommand
{
    protected $group       = 'app';
    protected $name        = 'app:start';
    protected $description = 'Displays basic application information.';
    
        /**
     * The current port offset.
     *
     * @var int
     */
    protected $portOffset = 0;

    /**
     * The max number of ports to attempt to serve from
     *
     * @var int
     */
    protected $tries = 10;

    public function run(array $params)
    {
        // Collect any user-supplied options and apply them.
        $php  = escapeshellarg(CLI::getOption('php') ?? PHP_BINARY);
        $host = CLI::getOption('host') ?? 'localhost';
        $port = (int) (CLI::getOption('port') ?? 8080) + $this->portOffset;

        // Get the party started.
        CLI::write('CodeIgniter development server started on http://' . $host . ':' . $port, 'green');
        CLI::write('Press Control-C to stop.');

        // Set the Front Controller path as Document Root.
        $docroot = escapeshellarg(FCPATH);

        // Mimic Apache's mod_rewrite functionality with user settings.
        $rewrite = escapeshellarg(__DIR__ . '/../../vendor/codeigniter4/framework/system/Commands/Server/rewrite.php');

        

        // Get the party started.
        CLI::write('API on http://' . $host . ':' . $port, 'green');
        CLI::write('Press Control-C to stop.');

        $cmd = $php . ' -S ' . $host . ':' . $port . ' -t ' . $docroot . ' ' . $rewrite;
        $cmd = \str_replace('"', '', $cmd);
        if(substr(php_uname(), 0, 7) == "Windows"){
            pclose(popen("start /B ". $cmd, "r")); 
        }else {
            exec($cmd . " > /dev/null &"); 
        }

        passthru('npm run dev', $status);
         if ($status && $this->portOffset < $this->tries) {
            $this->portOffset++;
            $this->run($params);
        }
    }
}