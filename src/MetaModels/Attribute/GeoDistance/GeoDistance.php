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

namespace MetaModels\Attribute\GeoDistance;

use MetaModels\Attribute\BaseComplex;
use MetaModels\Attribute\IAttribute;
use MetaModels\Filter\Helper\Perimetersearch\LookUp\Provider\Container;

/**
 * This is the MetaModelAttribute class for handling numeric fields.
 *
 * @package    MetaModels
 * @subpackage AttributeNumeric
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 */
class GeoDistance extends BaseComplex
{

    /**
     * A internal list with values.
     *
     * @var array
     */
    protected static $data = array();

    /**
     * Retrieve the database.
     *
     * @return \Contao\Database
     */
    private function getDataBase()
    {
        return $this
            ->getMetaModel()
            ->getServiceContainer()
            ->getDatabase();
    }

    /**
     * {@inheritdoc}
     */
    public function sortIds($idList, $strDirection)
    {
        // Check if we have some id.
        if (empty($idList)) {
            return $idList;
        }

        // Get some settings.
        $objMetaModel = $this->getMetaModel();
        $getGeo       = $this->get('get_geo');
        $getLand      = $this->get('get_land');
        $service      = $this->get('lookupservice');

        // Check if we have a get param.
        if (empty($getGeo) || empty($service)) {
            return $idList;
        }

        // Get the params.
        $geo  = \Input::get($getGeo);
        $land = (\Input::get($getLand)) ?: '';

        // Check if we have some geo params.
        if (empty($geo) && empty($land)) {
            return $idList;
        }

        try {
            // Get the geo data.
            $objContainer = $this->lookupGeo($geo, $land);

            // Okay we cant find a entry. So search for nothing.
            if ($objContainer == null || $objContainer->hasError()) {
                return $idList;
            }

            if ($this->get('datamode') == 'single') {
                // Get the attribute.
                $objAttribute = $objMetaModel->getAttribute($this->get('single_attr_id'));

                // Search for the geolocation attribute.
                if ($objAttribute->get('type') == 'geolocation') {
                    $idList = $this->doSearchForAttGeolocation($objContainer, $idList);
                }
            } elseif ($this->get('datamode') == 'multi') {
                // Get the attributes.
                $objFirstAttribute  = $objMetaModel->getAttribute($this->get('first_attr_id'));
                $objSecondAttribute = $objMetaModel->getAttribute($this->get('second_attr_id'));

                // Search for two simple attributes.
                $idList = $this
                    ->doSearchForTwoSimpleAtt($objContainer, $idList, $objFirstAttribute, $objSecondAttribute);
            }
        } catch (\Exception $e) {
            // Should be never happened, just in case.
        }

        // Base implementation, do not perform any sorting.
        return $idList;
    }

    /**
     * Run the search for the complex attribute geolocation.
     *
     * @param Container $container The container with all information.
     *
     * @param array     $idList    A list with ids.
     *
     * @return array A list with all sorted id's.
     */
    protected function doSearchForAttGeolocation($container, $idList)
    {
        // Get location.y
        $lat    = $container->getLatitude();
        $lng    = $container->getLongitude();
        $subSQL = sprintf(
            'SELECT
                item_id,
                round(
                  sqrt(power(2 * pi() / 360 * (%1$s - latitude) * 6371, 2) 
                  + power(2 * pi() / 360 * (%2$s - longitude) * 6371 
                  * COS(2 * pi() / 360 * (%1$s + latitude) * 0.5), 2))) AS item_dist
            FROM
                tl_metamodel_geolocation
            WHERE
                item_id IN(%3$s) AND att_id = ?
            ORDER BY item_dist',
            $lat,
            $lng,
            implode(', ', $idList)
        );

        $objResult = \Database::getInstance()
                              ->prepare($subSQL)
                              ->execute($this->getMetaModel()->getAttribute($this->get('single_attr_id'))->get('id'));

        $newIdList = array();
        foreach ($objResult->fetchAllAssoc() as $item) {
            $id              = $item['item_id'];
            $distance        = $item['item_dist'];
            $newIdList[]     = $id;
            self::$data[$id] = $distance;
        }

        $diff = array_diff($idList, $newIdList);

        return array_merge($newIdList, $diff);
    }

    /**
     * Run the search for the complex attribute geolocation.
     *
     * @param Container  $container     The container with all information.
     *
     * @param array      $idList        The list with the current ID's.
     *
     * @param IAttribute $latAttribute  The attribute to filter on.
     *
     * @param IAttribute $longAttribute The attribute to filter on.
     *
     * @return array A list with all sorted id's.
     */
    protected function doSearchForTwoSimpleAtt($container, $idList, $latAttribute, $longAttribute)
    {
        // Get location.
        $lat     = $container->getLatitude();
        $lng     = $container->getLongitude();
        $intDist = $container->getDistance();
        $subSQL  = sprintf(
            'SELECT
                id,
                round
                (
                  sqrt
                  (
                    power
                    (
                      2 * pi() / 360 * (%1$s -  CAST(%3$s AS DECIMAL(10,6))   ) * 6371,2) 
                      + power(2 * pi() / 360 * (%2$s - CAST(%4$s AS DECIMAL(10,6))) 
                      * 6371 * COS(2 * pi() / 360 * (%1$s + CAST(%3$s AS DECIMAL(10,6))) * 0.5),2
                    )
                  )
                ) 
                AS item_dist
            FROM
                %6$s
            WHERE
                id IN(%5$s)
            ORDER BY item_dist',
            $lat,
            $lng,
            $latAttribute->getColName(),
            $longAttribute->getColName(),
            implode(', ', $idList),
            $this->getMetaModel()->getTableName()
        );

        $objResult = \Database::getInstance()
                              ->prepare($subSQL)
                              ->execute($intDist);

        $newIdList = array();
        foreach ($objResult->fetchAllAssoc() as $item) {
            $id              = $item['id'];
            $distance        = $item['item_dist'];
            $newIdList[]     = $id;
            self::$data[$id] = $distance;
        }

        $diff = array_diff($idList, $newIdList);

        return array_merge($newIdList, $diff);
    }

    /**
     * User the provider classes to make a look up.
     *
     * @param string $strAddress The full address to search for.
     *
     * @param string $strCountry The country as 2-letters form.
     *
     * @return Container|null Return the container with all information or null on error.
     */
    protected function lookupGeo($strAddress, $strCountry)
    {
        // Trim the data. Better!
        $strAddress = trim($strAddress);
        $strCountry = trim($strCountry);

        // First check cache.
        $objCacheResult = $this->getFromCache($strAddress, $strCountry);
        if ($objCacheResult !== null) {
            return $objCacheResult;
        }

        // If there is no data from the cache ask google.
        $arrLookupServices = deserialize($this->get('lookupservice'), true);
        if (!count($arrLookupServices)) {
            return false;
        }

        foreach ($arrLookupServices as $arrSettings) {
            try {
                $objCallbackClass = $this->getObjectFromName($arrSettings['lookupservice']);

                // Call the main function.
                if ($objCallbackClass != null) {
                    /** @var Container $objResult */
                    $objResult = $objCallbackClass->getCoordinates(null, null, null, $strCountry, $strAddress);

                    // Check if we have a result.
                    if (!$objResult->hasError()) {
                        return $objResult;
                    }
                }
            } catch (\RuntimeException $exc) {
                // Okay, we have an error try next one.
            }
        }

        // When we reach this point, we have no result, so return false.
        return null;
    }

    /**
     * Try to get a object from the given class.
     *
     * @param string $lookupClassName The name of the class.
     *
     * @return null|object
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    protected function getObjectFromName($lookupClassName)
    {
        // Check if we know this class.
        if (!isset($GLOBALS['METAMODELS']['filters']['perimetersearch']['resolve_class'][$lookupClassName])) {
            return null;
        }

        $sClass           = $GLOBALS['METAMODELS']['filters']['perimetersearch']['resolve_class'][$lookupClassName];
        $objCallbackClass = null;
        $oClass           = new \ReflectionClass($sClass);

        // Fetch singleton instance.
        if ($oClass->hasMethod('getInstance')) {
            $getInstanceMethod = $oClass->getMethod('getInstance');

            // Create a new instance.
            if ($getInstanceMethod->isStatic()) {
                $objCallbackClass = $getInstanceMethod->invoke(null);

                return $objCallbackClass;
            } else {
                $objCallbackClass = $oClass->newInstance();

                return $objCallbackClass;
            }
        } else {
            // Create a normal object.
            $objCallbackClass = $oClass->newInstance();

            return $objCallbackClass;
        }
    }

    /**
     * Get data from cache.
     *
     * @param string $address The address which where use for the search.
     *
     * @param string $country The country.
     *
     * @return Container|null
     */
    protected function getFromCache($address, $country)
    {
        // Check cache.
        $result = $this
            ->getDataBase()
            ->prepare('SELECT * FROM tl_metamodel_perimetersearch WHERE search = ? AND country = ?')
            ->execute($address, $country);

        // If we have no data just return null.
        if ($result->count() === 0) {
            return null;
        }

        // Build a new container.
        $container = new Container();
        $container->setLatitude($result->geo_lat);
        $container->setLongitude($result->geo_long);
        $container->setSearchParam($result->query);

        return $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributeSettingNames()
    {
        return array_merge(
            parent::getAttributeSettingNames(),
            array(
                'mandatory',
                'filterable',
                'searchable',
                'get_geo',
                'get_land',
                'lookupservice',
                'datamode',
                'single_attr_id',
                'first_attr_id',
                'second_attr_id'
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldDefinition($arrOverrides = array())
    {
        return array();
    }

    /**
     * This method is called to store the data for certain items to the database.
     *
     * @param mixed[] $arrValues The values to be stored into database. Mapping is item id=>value.
     *
     * @return void
     */
    public function setDataFor($arrValues)
    {
        // No-op.
    }

    /**
     * Retrieve the filter options of this attribute.
     *
     * Retrieve values for use in filter options, that will be understood by DC_ filter
     * panels and frontend filter select boxes.
     * One can influence the amount of returned entries with the two parameters.
     * For the id list, the value "null" represents (as everywhere in MetaModels) all entries.
     * An empty array will return no entries at all.
     * The parameter "used only" determines, if only really attached values shall be returned.
     * This is only relevant, when using "null" as id list for attributes that have pre configured
     * values like select lists and tags i.e.
     *
     * @param string[]|null $idList   The ids of items that the values shall be fetched from
     *                                (If empty or null, all items).
     *
     * @param bool          $usedOnly Determines if only "used" values shall be returned.
     *
     * @param array|null    $arrCount Array for the counted values.
     *
     * @return array All options matching the given conditions as name => value.
     */
    public function getFilterOptions($idList, $usedOnly, &$arrCount = null)
    {
        return array();
    }

    /**
     * This method is called to retrieve the data for certain items from the database.
     *
     * @param string[] $arrIds The ids of the items to retrieve.
     *
     * @return mixed[] The nature of the resulting array is a mapping from id => "native data" where
     *                 the definition of "native data" is only of relevance to the given item.
     */
    public function getDataFor($arrIds)
    {
        $return = array();
        foreach ($arrIds as $id) {
            if (isset(self::$data[$id])) {
                $return[$id] = self::$data[$id];
            } else {
                $return[$id] = -1;
            }
        }

        return $return;
    }

    /**
     * Remove values for items.
     *
     * @param string[] $arrIds The ids of the items to retrieve.
     *
     * @return void
     */
    public function unsetDataFor($arrIds)
    {
        // No-op.
    }
}
