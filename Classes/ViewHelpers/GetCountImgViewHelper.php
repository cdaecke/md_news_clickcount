<?php

declare(strict_types=1);

namespace Mediadreams\MdNewsClickcount\ViewHelpers;

/**
 *
 * This file is part of the "News click count" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2024 Christoph Daecke <typo3@mediadreams.org>
 */

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

final class GetCountImgViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        $this->registerArgument('newsUid', 'int', 'The uid of the news record', true);
        $this->registerArgument('pageUid', 'int', 'The uid of the page');
    }

    public function render(): string
    {
        // TODO: $this->arguments['pageUid'] is obsolet and can be removed in v5.0
        if (isset($this->arguments['pageUid'])) {
            trigger_error(
                'Parameter "pageUid" in "GetCountImgViewHelper" is deprecated since version 4.0.0, will be removed in version 5.0.0',
                E_USER_DEPRECATED
            );
        }

        $request = $this->getRequest();

        if (!$request instanceof ServerRequestInterface) {
            return '';
        }

        $siteUrl = $request->getAttribute('normalizedParams')->getSiteUrl();

        return sprintf(
            '<img src="%smd-newsimg-%s.gif" alt="" width="1" height="1" aria-hidden="true" />',
            $siteUrl,
            $this->arguments['newsUid']
        );
    }

    private function getRequest(): ServerRequestInterface|null
    {
        if ((new (Typo3Version::class))->getMajorVersion() <= 12) {
            // Todo: remove on dropping TYPO3 v12 support
            return $this->renderingContext->getRequest();
        }
        if ($this->renderingContext->hasAttribute(ServerRequestInterface::class)) {
            return $this->renderingContext->getAttribute(ServerRequestInterface::class);
        }
        return null;
    }
}
