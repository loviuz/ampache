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
 *
 */

namespace Ampache\Repository;

use Ampache\Repository\Model\ChannelInterface;

interface ChannelRepositoryInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getDataById(int $channelId): array;

    public function getNextPort(int $defaultPort): int;

    public function delete(ChannelInterface $channel): void;

    public function updateListeners(
        int $channelId,
        int $listeners,
        int $peakListeners,
        int $connections
    ): void;

    public function updateStart(
        int $channelId,
        int $startDate,
        string $address,
        int $port,
        int $pid
    ): void;

    public function stop(
        int $channelId
    ): void;

    public function create(
        string $name,
        string $description,
        string $url,
        string $objectType,
        int $objectId,
        string $interface,
        int $port,
        string $adminPassword,
        int $isPrivate,
        int $maxListeners,
        int $random,
        int $loop,
        string $streamType,
        int $bitrate
    ): void;

    public function update(
        int $channelId,
        string $name,
        string $description,
        string $url,
        string $interface,
        int $port,
        string $adminPassword,
        int $isPrivate,
        int $maxListeners,
        int $random,
        int $loop,
        string $streamType,
        int $bitrate,
        int $objectId
    ): void;
}