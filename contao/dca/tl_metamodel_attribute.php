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
 * @subpackage AttributeGeorange
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @copyright  The MetaModels team.
 * @license    LGPL.
 * @filesource
 */

/**
 * Table tl_metamodel_attribute
 */
$GLOBALS['TL_DCA']['tl_metamodel_attribute']['metapalettes']['georange extends _simpleattribute_'] = array
(
    '+parameter' => array('get_geo', 'get_land'),
    '+data' => array('datamode', 'lookupservice'),
);

// Subpalettes.
$GLOBALS['TL_DCA']['tl_metamodel_attribute']['metasubselectpalettes']['datamode']['single'] =
    array('single_attr_id');
$GLOBALS['TL_DCA']['tl_metamodel_attribute']['metasubselectpalettes']['datamode']['multi']  =
    array(
        'first_attr_id',
        'second_attr_id'
    );

// Fields.
$GLOBALS['TL_DCA']['tl_metamodel_attribute']['fields']['get_geo'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_attribute']['get_geo'],
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => array
    (
        'tl_class' => 'w50'
    ),
);

$GLOBALS['TL_DCA']['tl_metamodel_attribute']['fields']['get_land'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_attribute']['get_land'],
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => array
    (
        'tl_class' => 'w50'
    ),
);

$GLOBALS['TL_DCA']['tl_metamodel_attribute']['fields']['lookupservice'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_attribute']['lookupservice'],
    'exclude'   => true,
    'inputType' => 'multiColumnWizard',
    'eval'      => array
    (
        'tl_class'     => 'clr',
        'columnFields' => array
        (
            'lookupservice' => array
            (
                'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_attribute']['lookupservice'],
                'exclude'   => true,
                'inputType' => 'select',
                'eval'      => array
                (
                    'includeBlankOption' => true,
                    'mandatory'          => true,
                    'chosen'             => true,
                    'style'              => 'width:250px',
                )
            ),
        ),
    )
);

$GLOBALS['TL_DCA']['tl_metamodel_attribute']['fields']['datamode'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_attribute']['datamode'],
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => array('single', 'multi'),
    'reference' => $GLOBALS['TL_LANG']['tl_metamodel_attribute']['datamode_options'],
    'eval'      => array
    (
        'doNotSaveEmpty'     => true,
        'alwaysSave'         => true,
        'submitOnChange'     => true,
        'includeBlankOption' => true,
        'mandatory'          => true,
        'tl_class'           => 'clr',
    )
);


$GLOBALS['TL_DCA']['tl_metamodel_attribute']['fields']['single_attr_id'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_attribute']['single_attr_id'],
    'exclude'   => true,
    'inputType' => 'select',
    'eval'      => array
    (
        'doNotSaveEmpty'     => true,
        'alwaysSave'         => true,
        'submitOnChange'     => true,
        'includeBlankOption' => true,
        'mandatory'          => true,
        'tl_class'           => 'w50',
        'chosen'             => true
    )
);

$GLOBALS['TL_DCA']['tl_metamodel_attribute']['fields']['first_attr_id'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_attribute']['first_attr_id'],
    'exclude'   => true,
    'inputType' => 'select',
    'eval'      => array
    (
        'doNotSaveEmpty'     => true,
        'alwaysSave'         => true,
        'submitOnChange'     => true,
        'includeBlankOption' => true,
        'mandatory'          => true,
        'tl_class'           => 'w50',
        'chosen'             => true
    )
);

$GLOBALS['TL_DCA']['tl_metamodel_attribute']['fields']['second_attr_id'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_attribute']['second_attr_id'],
    'exclude'   => true,
    'inputType' => 'select',
    'eval'      => array
    (
        'doNotSaveEmpty'     => true,
        'alwaysSave'         => true,
        'submitOnChange'     => true,
        'includeBlankOption' => true,
        'mandatory'          => true,
        'tl_class'           => 'w50',
        'chosen'             => true
    )
);

