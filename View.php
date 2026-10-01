<?php

declare(strict_types=1);
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace View;

use Propel\Runtime\Connection\ConnectionInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator;
use Thelia\Core\Install\Database;
use Thelia\Module\BaseModule;

class View extends BaseModule
{
    public const DOMAIN = 'view';

    public function preActivation(?ConnectionInterface $con = null): bool
    {
        (new Database($con))->insertSql(null, [__DIR__.'/Config/thelia.sql']);

        return true;
    }

    public static function configureServices(ServicesConfigurator $servicesConfigurator): void
    {
        $servicesConfigurator
            ->load(self::getModuleCode().'\\', __DIR__)
            ->exclude([
                __DIR__.'/Config',
                __DIR__.'/I18n',
                __DIR__.'/Model',
                __DIR__.'/templates',
                __DIR__.'/Tests',
                __DIR__.'/View.php',
            ])
            ->autowire(true)
            ->autoconfigure(true);
    }
}
