<?php
declare(strict_types=1);

namespace Mediadreams\MdNewsClickcount\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * Class CleanupLogCommand
 * call this command in terminal with `vendor/bin/typo3 mdNewsClickcount:cleanupLogCommand`
 *
 * @package Mediadreams\MdNewsClickcount\Command
 */
class CleanupLogCommand extends Command
{
    /**
     * Configure the command by defining the name, options and arguments
     */
    protected function configure()
    {
        $this->setHelp('Cleans up the click count log according to the extension settings');
    }

    /**
     * Executes the command for showing sys_log entries
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_mdnewsclickcount_log');

        $queryBuilder
            ->delete('tx_mdnewsclickcount_log')
            ->where(
                $queryBuilder->expr()->lt(
                    'log_date',
                    $queryBuilder->createNamedParameter($this->getAllowedTimeFrame(), \PDO::PARAM_STR)
                )
            )
            ->executeStatement();

        $io->writeln('Click count successfully removed.');
        return Command::SUCCESS;
    }

    /**
     * @return string Formatted date
     */
    /**
     * @return false|string
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    protected function getAllowedTimeFrame()
    {
        $time = 86400 * ($this->getDaysForNextCount() + 1);
        return date('Y-m-d', $GLOBALS['EXEC_TIME'] - $time);
    }

    /**
     * @return int
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    private function getDaysForNextCount(): int
    {
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);
        $typoscript = $configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
        );

        if (isset($typoscript['plugin.']['tx_mdnewsclickcount_count.']['settings.']['daysForNextCount'])) {
            return (int)$typoscript['plugin.']['tx_mdnewsclickcount_count.']['settings.']['daysForNextCount'];
        } else {
            return 0;
        }
    }
}
