<?php

use Snowdog\DevTest\Command\MigrateCommand;
use Snowdog\DevTest\Command\WarmCommand;
use Snowdog\DevTest\Component\CommandRepository;
use Snowdog\DevTest\Component\Menu;
use Snowdog\DevTest\Component\Migrations;
use Snowdog\DevTest\Component\RouteRepository;
use Snowdog\DevTest\Controller\CreatePageAction;
use Snowdog\DevTest\Controller\CreateWebsiteAction;
use Snowdog\DevTest\Controller\IndexAction;
use Snowdog\DevTest\Controller\LoginAction;
use Snowdog\DevTest\Controller\LoginFormAction;
use Snowdog\DevTest\Controller\LogoutAction;
use Snowdog\DevTest\Controller\RegisterAction;
use Snowdog\DevTest\Controller\RegisterFormAction;
use Snowdog\DevTest\Controller\VarnishesAction;
use Snowdog\DevTest\Controller\WebsiteAction;
use Snowdog\DevTest\Menu\LoginMenu;
use Snowdog\DevTest\Menu\RegisterMenu;
use Snowdog\DevTest\Menu\VarnishMenu;
use Snowdog\DevTest\Menu\WebsitesMenu;

RouteRepository::registerRoute('GET', '/', IndexAction::class, 'execute');
RouteRepository::registerRoute('GET', '/login', LoginFormAction::class, 'execute');
RouteRepository::registerRoute('POST', '/login', LoginAction::class, 'execute');
RouteRepository::registerRoute('GET', '/logout', LogoutAction::class, 'execute');
RouteRepository::registerRoute('GET', '/register', RegisterFormAction::class, 'execute');
RouteRepository::registerRoute('POST', '/register', RegisterAction::class, 'execute');
RouteRepository::registerRoute('GET', '/website/{id:\d+}', WebsiteAction::class, 'execute');
RouteRepository::registerRoute('POST', '/website', CreateWebsiteAction::class, 'execute');
RouteRepository::registerRoute('POST', '/website_import', \Snowdog\DevTest\Controller\ImportWebsiteAction::class, 'execute');
RouteRepository::registerRoute('POST', '/page', CreatePageAction::class, 'execute');
RouteRepository::registerRoute('GET', '/varnish', VarnishesAction::class, 'execute');
RouteRepository::registerRoute('POST', '/varnish', \Snowdog\DevTest\Controller\CreateVarnishAction::class, 'execute');
RouteRepository::registerRoute('POST', '/varnish_link', \Snowdog\DevTest\Controller\CreateVarnishLinkAction::class, 'execute');

CommandRepository::registerCommand('migrate_db', MigrateCommand::class);
CommandRepository::registerCommand('warm [id]', WarmCommand::class);
CommandRepository::registerCommand('parse:sitemap [userId]', \Snowdog\DevTest\Command\ParseSitemapCommand::class);

Menu::register(LoginMenu::class, 200);
Menu::register(VarnishMenu::class, 100);
Menu::register(RegisterMenu::class, 250);
Menu::register(WebsitesMenu::class, 10);

Migrations::registerComponentMigration('Snowdog\\DevTest', 3);