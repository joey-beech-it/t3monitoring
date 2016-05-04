<?php
namespace T3Monitor\T3monitoring\Domain\Repository;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Domain\Model\Dto\CoreFilterDemand;

/**
 * The repository for Cores
 */
class CoreRepository extends BaseRepository
{
    const USED_ALL = 0;
    const USED_ONLY = 1;

    public function findByDemand(CoreFilterDemand $demand)
    {
        $query = $this->getQuery();

        $constraints = [];

        // used
        $constraints[] = $query->equals('isUsed', $demand->getUsage());

        if (!empty($constraints)) {
            $query->matching(
                $query->logicalAnd($constraints)
            );
        }

        return $query->execute();
    }

    public function findAllCoreVersions($mode = self::USED_ONLY)
    {
        $query = $this->getQuery();
        if ($mode > 0) {
            $query->matching($query->logicalAnd(
                $query->equals('isUsed', ($mode === self::USED_ONLY ? 1 : 0))
            ));
        }
        return $query->execute();
    }

    public function findByVersionAsInteger($version)
    {
        $query = $this->getQuery();
        return $query->matching(
            $query->logicalAnd(
                $query->equals('versionInteger', $version)
            ))->execute()->getFirst();
    }

}