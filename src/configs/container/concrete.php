<?php

declare(strict_types=1);

use App\Auth;
use Slim\App;
use App\Config;
use App\DTOs\SessionConfig;
use App\Entity\User;
use App\Enums\AppEnvironmentEnum;
use App\Enums\SessionSamesiteOptionEnum;
use App\Interfaces\AuthInterface;
use App\Interfaces\SessionInterface;
use App\Interfaces\UserInterface;
use App\Interfaces\UserProviderInterface;
use App\Services\UserProviderService;
use App\Session;
use Slim\Views\Twig;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;
use Symfony\Component\Asset\Package;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Csrf\Guard;
use Slim\Factory\AppFactory;
use Symfony\Component\Asset\Packages;
use Symfony\Bridge\Twig\Extension\AssetExtension;
use Symfony\WebpackEncoreBundle\Asset\TagRenderer;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookup;
use Symfony\WebpackEncoreBundle\Twig\EntryFilesTwigExtension;
use Symfony\Component\Asset\VersionStrategy\JsonManifestVersionStrategy;

return [
    App::class => function(ContainerInterface $container) {
        AppFactory::setContainer($container);

        $middleware = require CONFIG_PATH . '/middlewares.php';
        $route = require CONFIG_PATH . '/web/routes.php';

        $app =  AppFactory::create();

        $route($app);
        $middleware($app, $container);

        return $app;
    },
    Config::class => fn() => new config($_ENV),
    EntityManager::class => function(Config $config) {
        $paths = [__DIR__ . '/../../app/Entity'];
        $isDevMode = false;

        $ormConfig = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);
        $connection = DriverManager::getConnection($config->get('db'), $ormConfig);
        
        return new EntityManager($connection, $ormConfig);
    },


    Twig::class => function(ContainerInterface $container, Config $config) {
        $twig = Twig::create(VIEWS_PATH, [
            'cache' => STORAGE_PATH . '/cache/templates',
            'auto_reload' => AppEnvironmentEnum::isDevelopment($config->get('app_environment')),
        ]);

        $twig->addExtension(new EntryFilesTwigExtension($container));
        $twig->addExtension(new AssetExtension($container->get('webpack_encore.packages')));

        return $twig;
    },
    'webpack_encore.packages' => fn() => new Packages(
        new Package(new JsonManifestVersionStrategy(BUILD_PATH . '/manifest.json'))
    ),
    'webpack_encore.tag_renderer' => fn(ContainerInterface $container) => new TagRenderer(
        new EntrypointLookup(BUILD_PATH . '/entrypoints.json'),
        $container->get('webpack_encore.packages')
    ),

    ResponseFactoryInterface::class => fn(App $app) => $app->getResponseFactory(),
    SessionInterface::class => function(Config $config) {
        return new Session(new SessionConfig(
            $config->get('session')['name'],
            $config->get('session')['secure'],
            $config->get('session')['httpOnly'],
            SessionSamesiteOptionEnum::from($config->get('session')['sameSite']) ?? 'lax',
        ));
    },
    AuthInterface::class => fn(ContainerInterface $container) => $container->get(Auth::class),
    UserInterface::class => fn(ContainerInterface $container) => $container->get(User::class),
    UserProviderInterface::class => fn(ContainerInterface $container) => $container->get(UserProviderService::class),
    'csrf' => fn(ResponseFactoryInterface $responseFactory) => new Guard($responseFactory, persistentTokenMode: true),
];