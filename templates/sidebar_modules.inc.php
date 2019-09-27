<?php
/* vim:set softtabstop=4 shiftwidth=4 expandtab: */
/**
 *
 * LICENSE: GNU Affero General Public License, version 3 (AGPLv3)
 * Copyright 2001 - 2019 Ampache.org
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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
?>
<ul class="sb2" id="sb_modules">
<li><h4 class="header"><span class="sidebar-header-title" title="<?php echo T_('Modules'); ?>"><?php echo T_('Modules'); ?></span><?php echo UI::get_icon('all', T_('Expand/Collapse'), 'modules', 'header-img ' . ((filter_has_var(INPUT_COOKIE, 'sb_modules')) ? $_COOKIE['sb_modules'] : 'expanded')); ?></h4>
        <ul class="sb3" id="sb_Modules">
        <li id="sb_preferences_mo_localplay"><a href="<?php echo $web_path; ?>/admin/modules.php?action=show_localplay"><?php echo T_('Localplay Modules'); ?></a></li>
        <li id="sb_preferences_mo_catalog_types"><a href="<?php echo $web_path; ?>/admin/modules.php?action=show_catalog_types"><?php echo T_('Catalog Modules'); ?></a></li>
        <li id="sb_preferences_mo_plugins"><a href="<?php echo $web_path; ?>/admin/modules.php?action=show_plugins"><?php echo T_('Available Plugins'); ?></a></li>
        </ul>
</li>
  <li><h4 class="header"><span class="sidebar-header-title" title="<?php echo T_('Other Tools'); ?>"><?php echo T_('Other Tools'); ?></span><?php echo UI::get_icon('all', T_('Expand/Collapse'), 'md_other_tools', 'header-img ' . ((filter_has_var(INPUT_COOKIE, 'sb_md_other_tools')) ? $_COOKIE['sb_md_other_tools'] : 'expanded')); ?></h4>
    <ul class="sb3" id="sb_admin_ot">
      <li id="sb_admin_ot_Duplicates"><a href="<?php echo $web_path; ?>/admin/duplicates.php"><?php echo T_('Find Duplicates'); ?></a></li>
      <li id="sb_admin_ot_Mail"><a href="<?php echo $web_path; ?>/admin/mail.php"><?php echo T_('Mail Users'); ?></a></li>
    </ul>
  </li>
<!--
<?php if (AmpConfig::get('allow_democratic_playback')) {
    ?>
  <li><h4><?php echo T_('Democratic'); ?></h4>
    <ul class="sb3" id="sb_home_democratic">
      <li id="sb_home_democratic_playlist"><a href="<?php echo $web_path; ?>/democratic.php?action=manage_playlists"><?php echo T_('Manage Playlist'); ?></a></li>
    </ul>
  </li>
<?php
} ?>
-->
</ul>