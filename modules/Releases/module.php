<?php
/*
 *	Made by Aberdeener
 *
 *  License: MIT
 *
 *  Releases module file
 */

class Releases_Module extends Module
{
    private Language $_language;

    public function __construct(Pages $pages, Language $language)
    {
        $this->_language = $language;

        $name = 'Releases';
        $author = '<a href="https://tadhg.sh" target="_blank" rel="nofollow noopener">Aberdeener</a>';
        $module_version = '0.1.0';
        $nameless_version = '2.0.0-pr12';

        parent::__construct($this, $name, $author, $module_version, $nameless_version);

        $pages->add('Releases', '/panel/releases', 'pages/panel/releases.php');
        $pages->add('Releases', '/panel/releases/edit', 'pages/panel/labels.php');

        $pages->add('Releases', '/api/update_check', 'pages/api/update_check.php');
        $pages->add('Releases', '/api/latest_update', 'pages/api/latest_update.php');
        $pages->add('Releases', '/api/all_updates', 'pages/api/all_updates.php');
    }

    public function onInstall()
    {
        $queries = new Queries();

        if (!$queries->tableExists('releases')) {
            $queries->createTable('releases', "`id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(36) NOT NULL UNIQUE, `version_tag` varchar(36) NOT NULL UNIQUE, `github_release_id` int(11) NOT NULL UNIQUE, `required_version` varchar(36) NOT NULL UNIQUE, `urgent` int(1) NOT NULL, `install_instructions` text NOT NULL, `created_at` int(36) NOT NULL, PRIMARY KEY (id)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
        }
    }

    public function onUninstall()
    {
    }

    public function onEnable()
    {
    }

    public function onDisable()
    {
    }

    public function onPageLoad($user, $pages, $cache, $smarty, $navs, $widgets, $template)
    {
        PermissionHandler::registerPermissions('Releases', array(
            'admincp.releases' => $this->_language->get('moderator', 'staff_cp') . ' &raquo; Releases'
        ));

        if (!defined('BACK_END')) {
            return;
        }

        if (!$user->hasPermission('admincp.releases')) {
            return;
        }

        $cache->setCache('navbar_icons');

        $cache->setCache('panel_sidebar');
        if (!$cache->isCached('releases_order')) {
            $order = 13;
            $cache->store('releases_order', 13);
        } else {
            $order = $cache->retrieve('releases_order');
        }

        if (!$cache->isCached('releases_icon')) {
            $icon = '<i class="nav-icon fas fa-file-upload"></i>';
            $cache->store('releases_icon', $icon);
        } else {
            $icon = $cache->retrieve('releases_icon');
        }

        $navs[2]->add('releases_divider', 'Releases', 'divider', 'top', null, $order, '');
        $navs[2]->add('releases', 'Releases', URL::build('/panel/releases'), 'top', null, $order + 0.2, $icon);
    }

    function getDebugInfo(): array
    {
        return [];
    }
}