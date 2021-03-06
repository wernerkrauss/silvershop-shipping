<?php

namespace SilverShop\Shipping\Tasks;

use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DataObject;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\YamlFixture;
use SilverStripe\ORM\DB;
use SilverStripe\Core\Extension;


/**
 * @package silvershop-shipping
 */
class PopulateTableShippingTask extends BuildTask
{
    protected $title = "Populate Table Shipping Methods";

    protected $description = 'If no table shipping methods exist, it creates multiple different setups of table shipping.';

    public function run($request = null)
    {
        if (!DataObject::get_one('TableShippingMethod')) {
            $factory = Injector::inst()->create('FixtureFactory');
            $fixture = new YamlFixture('silvershop-shipping/tests/fixtures/TableShippingMethod.yml');
            $fixture->writeInto($factory);
            DB::alteration_message('Created table shipping methods', 'created');
        } else {
            DB::alteration_message('Some table shipping methods already exist. None were created.');
        }
    }
}

/**
 * Makes PopulateTableShippingTask get run before PopulateShopTask is run
 *
 * @package silvershop-shipping
 */
class PopulateShopTableShippingTask extends Extension
{
    public function beforePopulate()
    {
        $task = new PopulateTableShippingTask();
        $task->run();
    }
}
