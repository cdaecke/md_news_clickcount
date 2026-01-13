<?php

declare(strict_types=1);

namespace Mediadreams\MdNewsClickcount\Domain\Model;

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
 * Extend news model of ext:news
 */
class News extends \GeorgRinger\News\Domain\Model\News
{

    /**
     * Number of views of news record.
     *
     * @var int
     */
    protected $mdNewsClickcountCount = 0;

    /**
     * Returns the mdNewsClickcountCount
     *
     * @return int $mdNewsClickcountCount
     */
    public function getMdNewsClickcountCount()
    {
        return $this->mdNewsClickcountCount;
    }

    /**
     * Sets the mdNewsClickcountCount
     *
     * @param int $mdNewsClickcountCount
     * @return void
     */
    public function setMdNewsClickcountCount($mdNewsClickcountCount)
    {
        $this->mdNewsClickcountCount = $mdNewsClickcountCount;
    }

    /**
     * add click
     *
     * @return void
     */
    public function addClick()
    {
        $this->mdNewsClickcountCount++;
    }
}
