<?php
/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 *
 * @package    MetaModels
 * @subpackage AttributeGeoDistance
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @copyright  The MetaModels team.
 * @license    LGPL.
 * @filesource
 */

namespace MetaModels\Attribute\GeoDistance;

use MetaModels\Attribute\AbstractAttributeTypeFactory;

/**
 * Attribute type factory for numeric attributes.
 */
class AttributeTypeFactory extends AbstractAttributeTypeFactory
{
    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        parent::__construct();

        $this->typeName  = 'geodistance';
        $this->typeIcon  = 'system/modules/metamodelsattribute_geodistance/html/numeric.png';
        $this->typeClass = 'MetaModels\Attribute\GeoDistance\GeoDistance';
    }
}
