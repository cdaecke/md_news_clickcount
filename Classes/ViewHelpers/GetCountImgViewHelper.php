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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

final class GetCountImgViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        $this->registerArgument('newsUid', 'int', 'The uid of the news record', true);
        $this->registerArgument('pageUid', 'int', 'The uid of the page', true);
    }

    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        $siteUrl = $renderingContext->getRequest()->getAttribute('normalizedParams')->getSiteUrl();

        return sprintf(
            '<img src="%smd-newsimg-%s.gif" alt="" width="1" height="1" aria-hidden="true" />',
            $siteUrl,
            $arguments['newsUid']
        );
    }
}
