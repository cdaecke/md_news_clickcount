<?php

declare(strict_types=1);

namespace Mediadreams\MdNewsClickcount\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ClearViewsCommand
 * call this command in terminal with `vendor/bin/typo3 mdNewsClickcount:clearViewsCommand`
 *
 * @package Mediadreams\MdNewsClickcount\Command
 */
class ClearViewsCommand extends Command
{
    /**
     * Configure the command by defining the name, options and arguments
     */
    protected function configure()
    {
        $this->setHelp('Resets the click counter for all ext:news records.');
    }

    /**
     * Executes the command for showing sys_log entries
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int error code
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_news_domain_model_news');

        $queryBuilder
            ->update('tx_news_domain_model_news')
            ->set('md_news_clickcount_count', 0)
            ->executeStatement();

        $io->writeln('Click count successfully removed.');
        return Command::SUCCESS;
    }
}
