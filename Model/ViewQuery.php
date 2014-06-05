<?php

namespace View\Model;

use View\Model\Base\ViewQuery as BaseViewQuery;

use Propel\Runtime\Propel;
use View\Model\Map\ViewTableMap;
use \PDO;
use View\Model\View as ChildView;
/**
 * Skeleton subclass for performing query and update operations on the 'view' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class ViewQuery extends BaseViewQuery
{
    public function findSSID($source, $source_id)
    {
    	$con = Propel::getServiceContainer()->getReadConnection(ViewTableMap::DATABASE_NAME);
	  	$sql = 'SELECT ID, VIEW, SOURCE, SOURCE_ID, CREATED_AT, UPDATED_AT FROM view WHERE SOURCE = :p0 AND SOURCE_ID = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $source, PDO::PARAM_STR);
            $stmt->bindValue(':p1', $source_id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildView();
            $obj->hydrate($row);
            ViewTableMap::addInstanceToPool($obj, (string) $source_id);
        }
        $stmt->closeCursor();

        return $obj;
    }
} // ViewQuery
