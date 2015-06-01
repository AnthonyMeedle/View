<?php

namespace View\Model\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use View\Model\View as ChildView;
use View\Model\ViewQuery as ChildViewQuery;
use View\Model\Map\ViewTableMap;

/**
 * Base class that represents a query for the 'view' table.
 *
 *
 *
 * @method     ChildViewQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildViewQuery orderByView($order = Criteria::ASC) Order by the view column
 * @method     ChildViewQuery orderBySource($order = Criteria::ASC) Order by the source column
 * @method     ChildViewQuery orderBySourceId($order = Criteria::ASC) Order by the source_id column
 * @method     ChildViewQuery orderBySubtreeView($order = Criteria::ASC) Order by the subtree_view column
 * @method     ChildViewQuery orderByChildrenView($order = Criteria::ASC) Order by the children_view column
 * @method     ChildViewQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildViewQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildViewQuery groupById() Group by the id column
 * @method     ChildViewQuery groupByView() Group by the view column
 * @method     ChildViewQuery groupBySource() Group by the source column
 * @method     ChildViewQuery groupBySourceId() Group by the source_id column
 * @method     ChildViewQuery groupBySubtreeView() Group by the subtree_view column
 * @method     ChildViewQuery groupByChildrenView() Group by the children_view column
 * @method     ChildViewQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildViewQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildViewQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildViewQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildViewQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildView findOne(ConnectionInterface $con = null) Return the first ChildView matching the query
 * @method     ChildView findOneOrCreate(ConnectionInterface $con = null) Return the first ChildView matching the query, or a new ChildView object populated from the query conditions when no match is found
 *
 * @method     ChildView findOneById(int $id) Return the first ChildView filtered by the id column
 * @method     ChildView findOneByView(string $view) Return the first ChildView filtered by the view column
 * @method     ChildView findOneBySource(string $source) Return the first ChildView filtered by the source column
 * @method     ChildView findOneBySourceId(int $source_id) Return the first ChildView filtered by the source_id column
 * @method     ChildView findOneBySubtreeView(string $subtree_view) Return the first ChildView filtered by the subtree_view column
 * @method     ChildView findOneByChildrenView(string $children_view) Return the first ChildView filtered by the children_view column
 * @method     ChildView findOneByCreatedAt(string $created_at) Return the first ChildView filtered by the created_at column
 * @method     ChildView findOneByUpdatedAt(string $updated_at) Return the first ChildView filtered by the updated_at column
 *
 * @method     array findById(int $id) Return ChildView objects filtered by the id column
 * @method     array findByView(string $view) Return ChildView objects filtered by the view column
 * @method     array findBySource(string $source) Return ChildView objects filtered by the source column
 * @method     array findBySourceId(int $source_id) Return ChildView objects filtered by the source_id column
 * @method     array findBySubtreeView(string $subtree_view) Return ChildView objects filtered by the subtree_view column
 * @method     array findByChildrenView(string $children_view) Return ChildView objects filtered by the children_view column
 * @method     array findByCreatedAt(string $created_at) Return ChildView objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return ChildView objects filtered by the updated_at column
 *
 */
abstract class ViewQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \View\Model\Base\ViewQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\View\\Model\\View', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildViewQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildViewQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \View\Model\ViewQuery) {
            return $criteria;
        }
        $query = new \View\Model\ViewQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildView|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ViewTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ViewTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return   ChildView A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, VIEW, SOURCE, SOURCE_ID, SUBTREE_VIEW, CHILDREN_VIEW, CREATED_AT, UPDATED_AT FROM view WHERE ID = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildView();
            $obj->hydrate($row);
            ViewTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildView|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ChildViewQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ViewTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildViewQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ViewTableMap::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildViewQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ViewTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ViewTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ViewTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the view column
     *
     * Example usage:
     * <code>
     * $query->filterByView('fooValue');   // WHERE view = 'fooValue'
     * $query->filterByView('%fooValue%'); // WHERE view LIKE '%fooValue%'
     * </code>
     *
     * @param     string $view The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildViewQuery The current query, for fluid interface
     */
    public function filterByView($view = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($view)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $view)) {
                $view = str_replace('*', '%', $view);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ViewTableMap::VIEW, $view, $comparison);
    }

    /**
     * Filter the query on the source column
     *
     * Example usage:
     * <code>
     * $query->filterBySource('fooValue');   // WHERE source = 'fooValue'
     * $query->filterBySource('%fooValue%'); // WHERE source LIKE '%fooValue%'
     * </code>
     *
     * @param     string $source The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildViewQuery The current query, for fluid interface
     */
    public function filterBySource($source = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($source)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $source)) {
                $source = str_replace('*', '%', $source);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ViewTableMap::SOURCE, $source, $comparison);
    }

    /**
     * Filter the query on the source_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySourceId(1234); // WHERE source_id = 1234
     * $query->filterBySourceId(array(12, 34)); // WHERE source_id IN (12, 34)
     * $query->filterBySourceId(array('min' => 12)); // WHERE source_id > 12
     * </code>
     *
     * @param     mixed $sourceId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildViewQuery The current query, for fluid interface
     */
    public function filterBySourceId($sourceId = null, $comparison = null)
    {
        if (is_array($sourceId)) {
            $useMinMax = false;
            if (isset($sourceId['min'])) {
                $this->addUsingAlias(ViewTableMap::SOURCE_ID, $sourceId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sourceId['max'])) {
                $this->addUsingAlias(ViewTableMap::SOURCE_ID, $sourceId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ViewTableMap::SOURCE_ID, $sourceId, $comparison);
    }

    /**
     * Filter the query on the subtree_view column
     *
     * Example usage:
     * <code>
     * $query->filterBySubtreeView('fooValue');   // WHERE subtree_view = 'fooValue'
     * $query->filterBySubtreeView('%fooValue%'); // WHERE subtree_view LIKE '%fooValue%'
     * </code>
     *
     * @param     string $subtreeView The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildViewQuery The current query, for fluid interface
     */
    public function filterBySubtreeView($subtreeView = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($subtreeView)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $subtreeView)) {
                $subtreeView = str_replace('*', '%', $subtreeView);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ViewTableMap::SUBTREE_VIEW, $subtreeView, $comparison);
    }

    /**
     * Filter the query on the children_view column
     *
     * Example usage:
     * <code>
     * $query->filterByChildrenView('fooValue');   // WHERE children_view = 'fooValue'
     * $query->filterByChildrenView('%fooValue%'); // WHERE children_view LIKE '%fooValue%'
     * </code>
     *
     * @param     string $childrenView The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildViewQuery The current query, for fluid interface
     */
    public function filterByChildrenView($childrenView = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($childrenView)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $childrenView)) {
                $childrenView = str_replace('*', '%', $childrenView);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ViewTableMap::CHILDREN_VIEW, $childrenView, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildViewQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(ViewTableMap::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(ViewTableMap::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ViewTableMap::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildViewQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(ViewTableMap::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(ViewTableMap::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ViewTableMap::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildView $view Object to remove from the list of results
     *
     * @return ChildViewQuery The current query, for fluid interface
     */
    public function prune($view = null)
    {
        if ($view) {
            $this->addUsingAlias(ViewTableMap::ID, $view->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the view table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ViewTableMap::DATABASE_NAME);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ViewTableMap::clearInstancePool();
            ViewTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildView or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildView object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public function delete(ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ViewTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ViewTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        ViewTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ViewTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     ChildViewQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(ViewTableMap::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     ChildViewQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(ViewTableMap::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     ChildViewQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(ViewTableMap::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     ChildViewQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(ViewTableMap::UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     ChildViewQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(ViewTableMap::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     ChildViewQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(ViewTableMap::CREATED_AT);
    }

} // ViewQuery
