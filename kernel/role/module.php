<?php
//
// Created on: <15-Aug-2002 14:36:10 bf>
//
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.2.0
// BUILD VERSION: 24182
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//

$Module = array( 'name' => 'eZRole' );

$ViewList = array();
$ViewList['list'] = array(
    'script' => 'list.php',
    'default_navigation_part' => 'ezusernavigationpart',
    'post_actions' => array( 'BrowseActionName' ),
    'unordered_params' => array( 'offset' => 'Offset' ),
    'params' => array(  ) );
$ViewList['edit'] = array(
    'script' => 'edit.php',
    'ui_context' => 'edit',
    'default_navigation_part' => 'ezusernavigationpart',
    'params' => array( 'RoleID' ) );
$ViewList['copy'] = array(
    'script' => 'copy.php',
    'ui_context' => 'edit',
    'default_navigation_part' => 'ezusernavigationpart',
    'params' => array( 'RoleID' ) );
$ViewList['policyedit'] = array(
    'script' => 'policyedit.php',
    'ui_context' => 'edit',
    'default_navigation_part' => 'ezusernavigationpart',
    'params' => array( 'PolicyID' ) );
$ViewList['view'] = array(
    'script' => 'view.php',
    'default_navigation_part' => 'ezusernavigationpart',
    'post_actions' => array( 'BrowseActionName' ),
    'params' => array( 'RoleID' ) );
$ViewList['assign'] = array(
    'script' => 'assign.php',
    'default_navigation_part' => 'ezusernavigationpart',
    'params' => array( 'RoleID', 'LimitIdent', 'LimitValue' ) );

?>