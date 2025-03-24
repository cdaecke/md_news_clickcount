<?php
declare(strict_types=1);

namespace Mediadreams\MdNewsClickcount\Command;

/**
 *
 * This file is part of the "News click count" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2021 Christoph Daecke <typo3@mediadreams.org>
 */

use Doctrine\DBAL\ParameterType;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
     * Executes the command for deleting `tx_mdnewsclickcount_log` entries
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
            ->getQueryBuilderForTable('tx_mdnewsclickcount_log');

        $queryBuilder
            ->delete('tx_mdnewsclickcount_log')
            ->where(
                $queryBuilder->expr()->lt(
                    'log_date',
                    $queryBuilder->createNamedParameter($this->getAllowedTimeFrame(), ParameterType::STRING)
                )
            )
            ->executeStatement();

        $io->writeln('Click count successfully removed.');
        return Command::SUCCESS;
    }

    /**
     * @return false|string Formatted date
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException
     */
    protected function getAllowedTimeFrame()
    {
        $configuration = GeneralUtility::makeInstance(ExtensionConfiguration::class)
            ->get('md_news_clickcount');

        $time = 86400 * ((int)$configuration['daysForNextCount'] + 1);
        return date('Y-m-d', $GLOBALS['EXEC_TIME'] - $time);
    }
}
