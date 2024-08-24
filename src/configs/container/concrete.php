<?php

declare(strict_types=1);

use App\Auth;
use App\Csrf;
use Clockwork\DataSource\DoctrineDataSource;
use Clockwork\Storage\FileStorage;
use Slim\App;
use App\Config;
use App\Session;
use App\Entity\User;
use Slim\Csrf\Guard;
use Slim\Views\Twig;
use Doctrine\ORM\ORMSetup;
use App\DTOs\SessionConfig;
use Slim\Factory\AppFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;
use App\Enums\AppEnvironmentEnum;
use App\Interfaces\AuthInterface;
use App\Interfaces\UserInterface;
use App\Interfaces\SessionInterface;
use Symfony\Component\Asset\Package;
use App\Services\UserProviderService;
use Psr\Container\ContainerInterface;
use Symfony\Component\Asset\Packages;
use App\Enums\SessionSamesiteOptionEnum;
use App\Interfaces\UserProviderInterface;
use Clockwork\Clockwork;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Interfaces\RouteParserInterface;
use Symfony\Bridge\Twig\Extension\AssetExtension;
use Symfony\Bridge\Twig\Mime\BodyRenderer;
use Symfony\WebpackEncoreBundle\Asset\TagRenderer;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookup;
use Symfony\WebpackEncoreBundle\Twig\EntryFilesTwigExtension;
use Symfony\Component\Asset\VersionStrategy\JsonManifestVersionStrategy;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\BodyRendererInterface;

return [
    App::class                      => function (ContainerInterface $container) {
        AppFactory::setContainer($container);

        $middleware = require CONFIG_PATH . '/middlewares.php';
        $route      = require CONFIG_PATH . '/web/routes.php';

        $app = AppFactory::create();

        $route($app);
        $middleware($app, $container);

        return $app;
    },
    Config::class                   => fn () => new config(require_once CONFIG_PATH . '/app.php'),
    EntityManager::class            => fn (Config $config) => new EntityManager(
        DriverManager::getConnection($config->get('doctrine.connection')),
        ORMSetup::createAttributeMetadataConfiguration(
            $config->get('doctrine.entity_dir'),
            $config->get('doctrine.dev_mode')
        )
    ),
    Twig::class                     => function (ContainerInterface $container, Config $config) {
        $twig = Twig::create(VIEWS_PATH, [
            'cache'       => STORAGE_PATH . '/cache/templates',
            'auto_reload' => AppEnvironmentEnum::isDevelopment($config->get('app_environment')),
        ]);

        $twig->addExtension(new EntryFilesTwigExtension($container));
        $twig->addExtension(new AssetExtension($container->get('webpack_encore.packages')));

        return $twig;
    },
    'webpack_encore.packages'       => fn () => new Packages(
        new Package(new JsonManifestVersionStrategy(BUILD_PATH . '/manifest.json'))
    ),
    'webpack_encore.tag_renderer'   => fn (ContainerInterface $container) => new TagRenderer(
        new EntrypointLookup(BUILD_PATH . '/entrypoints.json'),
        $container->get('webpack_encore.packages')
    ),

    ResponseFactoryInterface::class => fn (App $app) => $app->getResponseFactory(),
    SessionInterface::class         => function (Config $config) {
        return new Session(new SessionConfig(
            $config->get('session')['name'],
            $config->get('session')['secure'],
            $config->get('session')['httpOnly'],
            SessionSamesiteOptionEnum::from($config->get('session')['sameSite']) ?? 'lax',
        ));
    },
    AuthInterface::class            => fn (ContainerInterface $container) => $container->get(Auth::class),
    UserInterface::class            => fn (ContainerInterface $container) => $container->get(User::class),
    UserProviderInterface::class    => fn (ContainerInterface $container) => $container->get(UserProviderService::class),
    'csrf'                          => fn (ResponseFactoryInterface $responseFactory, Csrf $csrf) => new Guard(
        $responseFactory,
        persistentTokenMode: true,
        failureHandler: $csrf->failureHandler()
    ),
    MailerInterface::class          => function (Config $config) {
        $transport = Transport::fromDsn($config->get('mailer.dsn'));

        return new Mailer($transport);
    },
    BodyRendererInterface::class    => fn (Twig $twig) => new BodyRenderer($twig->getEnvironment()),
    Clockwork::class                => function (EntityManager $entityManager) {
        $clockwork = new Clockwork();

        $clockwork->storage(new FileStorage(STORAGE_PATH . '/clockwork'));
        $clockwork->addDataSource(new DoctrineDataSource($entityManager));

        return $clockwork;
    },
    RouteParserInterface::class     => fn (App $app) => $app->getRouteCollector()->getRouteParser()
];