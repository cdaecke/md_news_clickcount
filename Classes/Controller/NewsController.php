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

/**
 * NewsController
 */
class NewsController extends \GeorgRinger\News\Controller\NewsController
{

    /**
     * action mdIncreaseCount
     *
     * @param \Mediadreams\MdNewsClickcount\Domain\Model\News $news
     * @return string
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     */
    public function mdIncreaseCountAction(\Mediadreams\MdNewsClickcount\Domain\Model\News $news)
    {
        if ($news) {
            $news->addClick();
            $this->newsRepository->update($news);
        }

        return '';
    }
}
