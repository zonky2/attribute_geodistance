<?php

/**
 * This file is part of MetaModels/attribute_alias.
 *
 * (c) 2012-2016 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels
 * @subpackage AttributeGeoDistance
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @copyright  2012-2016 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_geodistance/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_metamodel_attribute']['typeOptions']['geodistance'] = 'Geo Distance';

$GLOBALS['TL_LANG']['tl_metamodel_attribute']['get_geo']        = array(
    'GET-Parameter for Geo',
    'Here you can add the GET-Parameter name for the geo lookup.'
);
$GLOBALS['TL_LANG']['tl_metamodel_attribute']['get_land']       = array(
    'GET-Parameter for country',
    'Here you can add the GET-Parameter name for the country lookup.'
);
$GLOBALS['TL_LANG']['tl_metamodel_attribute']['datamode']       = array(
    'Datamode',
    'Here you can choose if you have one single attribute or two attributes.'
);
$GLOBALS['TL_LANG']['tl_metamodel_attribute']['single_attr_id'] = array(
    'Attribute',
    'Choose the attribute with the latitude and longitude values.'
);
$GLOBALS['TL_LANG']['tl_metamodel_attribute']['first_attr_id']  = array(
    'Attribute - Latitude',
    'Choose the attribute for the latitude values.'
);
$GLOBALS['TL_LANG']['tl_metamodel_attribute']['second_attr_id'] = array(
    'Attribute - Longitude',
    'Choose the attribute for the longitude values.'
);
$GLOBALS['TL_LANG']['tl_metamodel_attribute']['lookupservice']  = array(
    'LookUp Services',
    'Here you can choose a look up service for resolving adress data.'
);
$GLOBALS['TL_LANG']['tl_metamodel_attribute']['countrymode']    = array(
    'Coutrymode',
    'Here you can choose how the language will used.'
);
$GLOBALS['TL_LANG']['tl_metamodel_attribute']['country_preset'] = array(
    'Coutry preset',
    'Here you can add a preset for the language.'
);
$GLOBALS['TL_LANG']['tl_metamodel_attribute']['country_get']    = array(
    'Coutry GET Parameter',
    'Here you can add a get parameter.'
);

/**
 * Legends
 */

$GLOBALS['TL_LANG']['tl_metamodel_attribute']['parameter_legend'] = 'Parameter';
$GLOBALS['TL_LANG']['tl_metamodel_attribute']['data_legend']      = 'Data Settings';

/**
 * Options
 */
$GLOBALS['TL_LANG']['tl_metamodel_attribute']['datamode_options']['single'] = 'Single Mode - One attribute';
$GLOBALS['TL_LANG']['tl_metamodel_attribute']['datamode_options']['multi']  = 'Multi Mode - Two attributes';
