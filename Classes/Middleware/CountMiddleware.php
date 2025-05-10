<?php

declare(strict_types=1);

namespace Mediadreams\MdNewsClickcount\Middleware;

/**
 *
 * This file is part of the "News click count" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2024 Christoph Daecke <typo3@mediadreams.org>
 */

use Doctrine\DBAL\ParameterType;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\UserAspect;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\NormalizedParams;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Http\Uri;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CountMiddleware implements MiddlewareInterface
{
    protected const LOG_TABLE = 'tx_mdnewsclickcount_log';

    protected array $configuration = [];

    public function __construct(
        protected ResponseFactoryInterface $responseFactory,
        protected Context $context,
        protected ExtensionConfiguration $extensionConfiguration,
        protected ConnectionPool $connectionPool
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var NormalizedParams $normalizedParams */
        $normalizedParams = $request->getAttribute('normalizedParams');
        $uri = $normalizedParams->getRequestUri();

        if (str_contains($uri, '/md-newsimg')) {
            $newsUid = $this->getUidFromUri($uri);
            if ($newsUid !== 0) {
                try {
                    $this->configuration = $this->extensionConfiguration->get('md_news_clickcount');
                } catch (\Exception $e) {
                }

                if ($this->countVisitEnabled($request)) {
                    $this->count($newsUid);
                }
            }

            return $this->createPixelResponse();
        }

        return $handler->handle($request);
    }

    protected function countVisitEnabled(ServerRequestInterface $request): bool
    {
        if ($request->hasHeader('referer')) {
            $referer = $request->getHeaderLine('referer');
            $refererRequest = new ServerRequest(new Uri($referer), 'GET');

            parse_str($refererRequest->getUri()->getQuery(), $params);

            if (!empty($params)) {
                $refererRequest = $refererRequest->withQueryParams($params);
                if ($refererRequest->getQueryParams()['tx_news_pi1']['news_preview'] ?? false) {
                    // Do not track news previews
                    return false;
                }
            }
        }

        if ($this->context->getAspect('backend.user')->isLoggedIn()) {
            return (bool)($this->configuration['countVisitsAlsoIfUserIsLoggedIntoBackend'] ?? false);
        }

        return true;
    }

    /**
     * Add count for news entry
     *
     * @throws \Doctrine\DBAL\Exception
     */
    protected function count(int $newsUid): void
    {
        if (
            !isset($this->configuration['daysForNextCount'])
            || (int)$this->configuration['daysForNextCount'] === 0
        ) {
            $count = 0;
        } else {
            $ip = GeneralUtility::getIndpEnv('REMOTE_ADDR');

            // Check log
            $queryBuilder = $this->connectionPool->getQueryBuilderForTable(self::LOG_TABLE);
            $count = (int)$queryBuilder
                ->count('*')
                ->from(self::LOG_TABLE)
                ->where(
                    $queryBuilder->expr()->eq('news', $queryBuilder->createNamedParameter($newsUid, ParameterType::INTEGER)),
                    $queryBuilder->expr()->eq('ip', $queryBuilder->createNamedParameter($ip, ParameterType::STRING)),
                    $queryBuilder->expr()->gte('log_date', $queryBuilder->createNamedParameter($this->getAllowedTimeFrame(), ParameterType::STRING)),
                )
                ->executeQuery()
                ->fetchOne();

            // Insert log
            $connection = $this->connectionPool->getConnectionForTable(self::LOG_TABLE);
            $connection->insert(self::LOG_TABLE, [
                'ip' => $ip,
                'news' => $newsUid,
                'log_date' => $this->getCurrentDate()
            ]);
        }

        if ($count === 0) {
            // Update click count in news record
            $connection = $this->connectionPool->getConnectionForTable('tx_news_domain_model_news');
            $connection->executeStatement('
                UPDATE tx_news_domain_model_news
                SET md_news_clickcount_count = md_news_clickcount_count+1
                WHERE uid=?',
                [$newsUid]
            );
        }
    }

    /**
     * Extract the News Uid from the URI string
     */
    protected function getUidFromUri(string $uri): int
    {
        $pattern = '/\d+(?=\.\bgif\b$)/';
        preg_match($pattern, $uri, $matches);
        if (count($matches) === 0) {
            return 0;
        }

        return (int)$matches[0];
    }

    protected function getCurrentDate(): string
    {
        return date('Y-m-d', $GLOBALS['EXEC_TIME']);
    }

    protected function getAllowedTimeFrame(): string
    {
        $time = 86400 * (int)$this->configuration['daysForNextCount'];
        return date('Y-m-d', $GLOBALS['EXEC_TIME'] - $time);
    }

    protected function createPixelResponse(): ResponseInterface
    {
        $pixel = base64_decode('R0lGODlhAQABAIAAAP///////yH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==');

        $response = $this->responseFactory->createResponse()
            ->withHeader('Content-Type', 'image/gif')
            ->withHeader('Cache-Control', 'max-age=0, no-cache, no-store, must-revalidate')
            ->withHeader('Pragma', 'no-cache')
            ->withHeader('Expires', '0');
        $response->getBody()->write($pixel);
        return $response;
    }
}
