<?php
declare(strict_types=1);

namespace Castor\HostAssessment;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\DependencyInjection\AddConsoleCommandPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

use function dirname;

require_once __DIR__ . '/../vendor/autoload.php';

$builder = new ContainerBuilder();
$rootDir = dirname(__DIR__);

$loader = new XmlFileLoader($builder, new FileLocator($rootDir . '/config'));
$loader->load('container.xml');

$builder->setParameter('app.base_dir', $rootDir);
$builder->addCompilerPass(new AddConsoleCommandPass())
    ->compile(true);

return $builder;
