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

        if ($newsUid !== null) {
            $newsUid = (int)$newsUid;

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
     * @return ServerRequestInterface
     */
    protected function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
