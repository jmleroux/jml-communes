<?php

namespace JML\Communes\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use ZipArchive;

class DownloadCommand extends Command
{
    protected $config;

    protected $output;

    public function __construct($name)
    {
        parent::__construct($name);

        $this->config = require __DIR__ . '/../../../../app/config/config.php';
        $this->output = new ConsoleOutput();
    }

    protected function configure()
    {
        $this->setName("jml:communes:download")
            ->setDescription("Download INSEE files");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->downloadAndExtract('communes', 'zip');
        $this->download('cantons', 'txt');
    }

    protected function download($filename, $extension)
    {
        $filepath = sprintf('%s/%s.%s', $this->config['files_directory'], $filename, $extension);
        file_put_contents($filepath, file_get_contents($this->config['urls'][$filename]));

        $this->output->writeln(sprintf('Downloaded <info>%s</info>', $filepath));

        return $filepath;
    }

    protected function downloadAndExtract($filename, $extension)
    {
        $zipFile = $this->download($filename, $extension);

        $zip = new ZipArchive();

        $resource = $zip->open($zipFile);

        if (true === $resource) {
            $zip->extractTo($this->config['files_directory']);
            $zip->close();

            unlink($zipFile);
            $this->output->writeln(sprintf('Extracted <info>%s</info>', $zipFile));
        }
    }
}
