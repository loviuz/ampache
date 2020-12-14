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

declare(strict_types=0);

namespace Ampache\Module\Application\SmartPlaylist;

use Ampache\Model\ModelFactoryInterface;
use Ampache\Model\Search;
use Ampache\Module\Application\ApplicationActionInterface;
use Ampache\Module\Application\Exception\AccessDeniedException;
use Ampache\Module\Authorization\GuiGatekeeperInterface;
use Ampache\Module\Util\Ui;
use Ampache\Module\Util\UiInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UpdatePlaylistAction implements ApplicationActionInterface
{
    public const REQUEST_KEY = 'update_playlist';

    private UiInterface $ui;

    private ModelFactoryInterface $modelFactory;

    public function __construct(
        UiInterface $ui,
        ModelFactoryInterface $modelFactory
    ) {
        $this->ui           = $ui;
        $this->modelFactory = $modelFactory;
    }

    public function run(ServerRequestInterface $request, GuiGatekeeperInterface $gatekeeper): ?ResponseInterface
    {
        $playlist = $this->modelFactory->createSearch(
            (int) $request->getParsedBody()['playlist_id'] ?? 0
        );

        if ($playlist->has_access()) {
            $playlist->parse_rules(Search::clean_request($_REQUEST));
            $playlist->update();
            $playlist->format();
        } else {
            throw new AccessDeniedException();
        }

        $this->ui->showHeader();

        $object_ids = $playlist->get_items();
        require_once Ui::find_template('show_search.inc.php');

        $this->ui->showQueryStats();
        $this->ui->showFooter();

        return null;
    }
}