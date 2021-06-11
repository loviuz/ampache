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

namespace Ampache\Module\Song\Deletion;

use Ampache\Repository\Model\Art;
use Ampache\Repository\Model\Rating;
use Ampache\Repository\Model\Song;
use Ampache\Repository\Model\Userflag;
use Ampache\Repository\RatingRepositoryInterface;
use Ampache\Repository\ShoutRepositoryInterface;
use Ampache\Repository\SongRepositoryInterface;
use Ampache\Repository\UserActivityRepositoryInterface;

final class SongDeleter implements SongDeleterInterface
{
    private ShoutRepositoryInterface $shoutRepository;

    private SongRepositoryInterface $songRepository;

    private UserActivityRepositoryInterface $useractivityRepository;

    private RatingRepositoryInterface $ratingRepository;

    public function __construct(
        ShoutRepositoryInterface $shoutRepository,
        SongRepositoryInterface $songRepository,
        UserActivityRepositoryInterface $useractivityRepository,
        RatingRepositoryInterface $ratingRepository
    ) {
        $this->shoutRepository        = $shoutRepository;
        $this->songRepository         = $songRepository;
        $this->useractivityRepository = $useractivityRepository;
        $this->ratingRepository       = $ratingRepository;
    }

    public function delete(Song $song): bool
    {
        if (file_exists($song->getFile())) {
            $deleted = unlink($song->getFile());
        } else {
            $deleted = true;
        }
        if ($deleted === true) {
            $songId  = $song->getId();
            $deleted = $this->songRepository->delete($songId);
            if ($deleted) {
                Art::garbage_collection('song', $songId);
                Userflag::garbage_collection('song', $songId);
                $this->ratingRepository->collectGarbage('song', $songId);
                $this->shoutRepository->collectGarbage('song', $songId);
                $this->useractivityRepository->collectGarbage('song', $songId);
            }
        } else {
            debug_event(__CLASS__, 'Cannot delete ' . $song->getFile() . 'file. Please check permissions.', 1);
        }

        return $deleted;
    }
}
