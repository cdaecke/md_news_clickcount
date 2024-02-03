<?php
declare(strict_types=1);

namespace Mediadreams\MdNewsClickcount\Controller;

/**
 *
 * This file is part of the "News click count" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2021 Christoph Daecke <typo3@mediadreams.org>
 */

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * NewsController
 */
class NewsController extends ActionController
{
    const LOG_TABLE = 'tx_mdnewsclickcount_log';

    /**
     * action mdIncreaseCount
     *
     * @return ResponseInterface
     *
     * @throws \Doctrine\DBAL\DBALException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     */
    public function mdIncreaseCountAction(): ResponseInterface
    {
        $newsUid = $this->getRequest()->getQueryParams()['tx_news_pi1']['news'] ?? null;

        if ($newsUid === null) {
            return $this->htmlResponse('');
        }

        $newsUid = (int)$newsUid;

        $ip = GeneralUtility::getIndpEnv('REMOTE_ADDR');
        $count = null;
        if (!isset($this->settings['daysForNextCount']) || (int)$this->settings['daysForNextCount'] === 0) {
            $count = 0;
        } else {
            // Check log
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable(self::LOG_TABLE);

            $count = (int)$queryBuilder
                ->count('*')
                ->from(self::LOG_TABLE)
                ->where(
                    $queryBuilder->expr()->eq('news', $queryBuilder->createNamedParameter($newsUid, \PDO::PARAM_STR)),
                    $queryBuilder->expr()->eq('ip', $queryBuilder->createNamedParameter($ip, \PDO::PARAM_STR)),
                    $queryBuilder->expr()->gte('log_date', $queryBuilder->createNamedParameter($this->getAllowedTimeFrame(), \PDO::PARAM_INT)),
                )
                ->executeQuery()
                ->fetchOne();

            // Insert log
            $connection = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getConnectionForTable(self::LOG_TABLE);

            $connection->insert(self::LOG_TABLE, [
                'ip' => $ip,
                'news' => $newsUid,
                'log_date' => $this->getCurrentDate()
            ]);
        }

        if ($count === 0) {
            // Update click count in news record
            // Do not use `$this->newsRepository->update($news)` since this will update the field `tstamp` as well.
            $connection = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getConnectionForTable('tx_news_domain_model_news');

            $connection->executeStatement('
                UPDATE tx_news_domain_model_news
                SET md_news_clickcount_count = md_news_clickcount_count+1
                WHERE uid=' . $newsUid
            );
        }

        // Return empty html
        return $this->htmlResponse('');
    }

    /**
     * Get current date
     *
     * @return string Formatted date
     */
    protected function getCurrentDate()
    {
        return date('Y-m-d', $GLOBALS['EXEC_TIME']);
    }

    /**
     * @return string Formatted date
     */
    protected function getAllowedTimeFrame()
    {
        $time = 86400 * (int)$this->settings['daysForNextCount'];
        return date('Y-m-d', $GLOBALS['EXEC_TIME'] - $time);
    }

    /**
     * @return ServerRequestInterface
     */
    protected function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
