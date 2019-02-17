<?php

namespace Bongo\S3AssetInstallerBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AssetInstallS3Command extends ContainerAwareCommand
{
    private $filesystem;
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('s3:assets:install')
            ->setDefinition(array(
                new InputArgument('target', InputArgument::OPTIONAL, 'The target bucket'),
            ))
            ->setDescription('This command will upload your assets to amazon s3');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $targetArg = rtrim($input->getArgument('target'), '/');
        $this->filesystem = $this->getContainer()->get('filesystem');
        $client = $this->getContainer()->get('bongo.s3client');
        
        $bundlesDir = $targetArg.'/bundles/';

        $io = new SymfonyStyle($input, $output);
        $io->newLine();

        $io->text('Trying to remove the old files first <info>in aws s3</info>.');

        $bucket = str_replace("s3://","", $targetArg);
        try {
            $objects= $client->listObjects([
                'Bucket' => $bucket,
                'Prefix' => 'bundles/',
            ]);


            if ($objects->hasKey('Contents')) {

                $io->progressStart();

                foreach ($objects->get('Contents') as $staleObject) {

                    $io->progressAdvance();
                    $client->deleteObject(['Bucket' => $bucket, 'Key' => $staleObject['Key']]);
                }

                $io->progressFinish();
                $io->write(" Old object Removed. Now installing new objects");
            }else{

                $io->write("No Previous files found in the mentioned bucket");
            }

        } catch (\Exception $e) {
            $io->error(sprintf('Some errors occurred while removing files. %s', $e->getMessage()));
        }


        foreach ($this->getContainer()->get('kernel')->getBundles() as $bundle) {
            if (!is_dir($originDir = $bundle->getPath() . '/Resources/public')) {
                continue;
            }

            $targetDir = $bundlesDir . preg_replace('/bundle$/', '', strtolower($bundle->getName()));

            if (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity()) {
                $message = sprintf("%s\n-> %s", $bundle->getName(), $targetDir);
            } else {
                $message = $bundle->getName();
            }

            try {

                $manager = new \Aws\S3\Transfer($client, $originDir, $targetDir);
                $manager->transfer();
                $rows[] = array(sprintf('<fg=green;options=bold>%s</>', '\\' === DIRECTORY_SEPARATOR ? 'OK' : "\xE2\x9C\x94" /* HEAVY CHECK MARK (U+2714) */), $message);
            } catch (\Exception $e) {

                $rows[] = array(sprintf('<fg=red;options=bold>%s</>', '\\' === DIRECTORY_SEPARATOR ? 'ERROR' : "\xE2\x9C\x98" /* HEAVY BALLOT X (U+2718) */), $message, $e->getMessage());
            }

        }

        $io->table(array('', 'Bundle', 'Method / Error'), $rows);

    }


}
