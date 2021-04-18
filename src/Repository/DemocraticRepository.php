<?php
/*
 * vim:set softtabstop=4 shiftwidth=4 expandtab:
 *
 * LICENSE: GNU Affero General Public License, version 3 (AGPL-3.0-or-later)
 * Copyright 2001 - 2020 Ampache.org
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace Ampache\Repository;

use Ampache\Config\ConfigContainerInterface;
use Ampache\Module\Cache\DatabaseObjectCacheInterface;
use Ampache\Module\System\Dba;
use Ampache\Repository\Model\Democratic;
use Ampache\Repository\Model\ModelFactoryInterface;

final class DemocraticRepository implements DemocraticRepositoryInterface
{
    private ConfigContainerInterface $configContainer;

    private ModelFactoryInterface $modelFactory;

    private DatabaseObjectCacheInterface $databaseObjectCache;

    public function __construct(
        ConfigContainerInterface $configContainer,
        ModelFactoryInterface $modelFactory,
        DatabaseObjectCacheInterface $databaseObjectCache
    ) {
        $this->configContainer     = $configContainer;
        $this->modelFactory        = $modelFactory;
        $this->databaseObjectCache = $databaseObjectCache;
    }

    /**
     * This returns the current users current playlist, or if specified
     * this current playlist of the user
     */
    public function getCurrent(
        int $accessLevel
    ): Democratic {
        $democraticId = $this->configContainer->get('democratic_id');

        if (!$democraticId) {
            $db_results   = Dba::read(
                'SELECT `id` FROM `democratic` WHERE `level` <= ?  ORDER BY `level` DESC,`primary` DESC',
                [$accessLevel]
            );
            $row          = Dba::fetch_assoc($db_results);
            $democraticId = (int) $row['id'];
        }

        return $this->modelFactory->createDemocratic($democraticId);
    }

    /**
     * This builds a vote cache of the objects we've got in the playlist
     *
     * @param int[] $objectIds
     */
    public function buildVoteCache(array $objectIds): void
    {
        if ($objectIds === []) {
            return;
        }

        $idlist = '(' . implode(',', $objectIds) . ')';
        $sql    = "SELECT `object_id`, COUNT(`user`) AS `count` " . "FROM `user_vote` " . "WHERE `object_id` IN $idlist GROUP BY `object_id`";

        $db_results = Dba::read($sql);

        while ($row = Dba::fetch_assoc($db_results)) {
            $this->databaseObjectCache->add('democratic_vote', $row['object_id'], [$row['count']]);
        }
    }
}