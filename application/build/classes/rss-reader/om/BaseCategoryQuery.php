<?php


/**
 * Base class that represents a query for the 'rss_category' table.
 *
 *
 *
 * @method CategoryQuery orderById($order = Criteria::ASC) Order by the id column
 * @method CategoryQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method CategoryQuery orderByParentCategoryId($order = Criteria::ASC) Order by the parent_category_id column
 *
 * @method CategoryQuery groupById() Group by the id column
 * @method CategoryQuery groupByName() Group by the name column
 * @method CategoryQuery groupByParentCategoryId() Group by the parent_category_id column
 *
 * @method CategoryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method CategoryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method CategoryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method CategoryQuery leftJoinParentCategory($relationAlias = null) Adds a LEFT JOIN clause to the query using the ParentCategory relation
 * @method CategoryQuery rightJoinParentCategory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ParentCategory relation
 * @method CategoryQuery innerJoinParentCategory($relationAlias = null) Adds a INNER JOIN clause to the query using the ParentCategory relation
 *
 * @method CategoryQuery leftJoinChildrenCategory($relationAlias = null) Adds a LEFT JOIN clause to the query using the ChildrenCategory relation
 * @method CategoryQuery rightJoinChildrenCategory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ChildrenCategory relation
 * @method CategoryQuery innerJoinChildrenCategory($relationAlias = null) Adds a INNER JOIN clause to the query using the ChildrenCategory relation
 *
 * @method CategoryQuery leftJoinFeed($relationAlias = null) Adds a LEFT JOIN clause to the query using the Feed relation
 * @method CategoryQuery rightJoinFeed($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Feed relation
 * @method CategoryQuery innerJoinFeed($relationAlias = null) Adds a INNER JOIN clause to the query using the Feed relation
 *
 * @method Category findOne(PropelPDO $con = null) Return the first Category matching the query
 * @method Category findOneOrCreate(PropelPDO $con = null) Return the first Category matching the query, or a new Category object populated from the query conditions when no match is found
 *
 * @method Category findOneByName(string $name) Return the first Category filtered by the name column
 * @method Category findOneByParentCategoryId(int $parent_category_id) Return the first Category filtered by the parent_category_id column
 *
 * @method array findById(int $id) Return Category objects filtered by the id column
 * @method array findByName(string $name) Return Category objects filtered by the name column
 * @method array findByParentCategoryId(int $parent_category_id) Return Category objects filtered by the parent_category_id column
 *
 * @package    propel.generator.rss-reader.om
 */
abstract class BaseCategoryQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseCategoryQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'rss-reader';
        }
        if (null === $modelName) {
            $modelName = 'Category';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new CategoryQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   CategoryQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return CategoryQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof CategoryQuery) {
            return $criteria;
        }
        $query = new CategoryQuery(null, null, $modelAlias);

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
     * @param     PropelPDO $con an optional connection object
     *
     * @return   Category|Category[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CategoryPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(CategoryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Category A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Category A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `name`, `parent_category_id` FROM `rss_category` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new Category();
            $obj->hydrate($row);
            CategoryPeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return Category|Category[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|Category[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return CategoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CategoryPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return CategoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CategoryPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id >= 12
     * $query->filterById(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CategoryQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CategoryPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CategoryPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CategoryPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CategoryQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CategoryPeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the parent_category_id column
     *
     * Example usage:
     * <code>
     * $query->filterByParentCategoryId(1234); // WHERE parent_category_id = 1234
     * $query->filterByParentCategoryId(array(12, 34)); // WHERE parent_category_id IN (12, 34)
     * $query->filterByParentCategoryId(array('min' => 12)); // WHERE parent_category_id >= 12
     * $query->filterByParentCategoryId(array('max' => 12)); // WHERE parent_category_id <= 12
     * </code>
     *
     * @see       filterByParentCategory()
     *
     * @param     mixed $parentCategoryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CategoryQuery The current query, for fluid interface
     */
    public function filterByParentCategoryId($parentCategoryId = null, $comparison = null)
    {
        if (is_array($parentCategoryId)) {
            $useMinMax = false;
            if (isset($parentCategoryId['min'])) {
                $this->addUsingAlias(CategoryPeer::PARENT_CATEGORY_ID, $parentCategoryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($parentCategoryId['max'])) {
                $this->addUsingAlias(CategoryPeer::PARENT_CATEGORY_ID, $parentCategoryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CategoryPeer::PARENT_CATEGORY_ID, $parentCategoryId, $comparison);
    }

    /**
     * Filter the query by a related Category object
     *
     * @param   Category|PropelObjectCollection $category The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CategoryQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByParentCategory($category, $comparison = null)
    {
        if ($category instanceof Category) {
            return $this
                ->addUsingAlias(CategoryPeer::PARENT_CATEGORY_ID, $category->getId(), $comparison);
        } elseif ($category instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CategoryPeer::PARENT_CATEGORY_ID, $category->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByParentCategory() only accepts arguments of type Category or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ParentCategory relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CategoryQuery The current query, for fluid interface
     */
    public function joinParentCategory($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ParentCategory');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'ParentCategory');
        }

        return $this;
    }

    /**
     * Use the ParentCategory relation Category object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   CategoryQuery A secondary query class using the current class as primary query
     */
    public function useParentCategoryQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinParentCategory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ParentCategory', 'CategoryQuery');
    }

    /**
     * Filter the query by a related Category object
     *
     * @param   Category|PropelObjectCollection $category  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CategoryQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByChildrenCategory($category, $comparison = null)
    {
        if ($category instanceof Category) {
            return $this
                ->addUsingAlias(CategoryPeer::ID, $category->getParentCategoryId(), $comparison);
        } elseif ($category instanceof PropelObjectCollection) {
            return $this
                ->useChildrenCategoryQuery()
                ->filterByPrimaryKeys($category->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByChildrenCategory() only accepts arguments of type Category or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ChildrenCategory relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CategoryQuery The current query, for fluid interface
     */
    public function joinChildrenCategory($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ChildrenCategory');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'ChildrenCategory');
        }

        return $this;
    }

    /**
     * Use the ChildrenCategory relation Category object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   CategoryQuery A secondary query class using the current class as primary query
     */
    public function useChildrenCategoryQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinChildrenCategory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ChildrenCategory', 'CategoryQuery');
    }

    /**
     * Filter the query by a related Feed object
     *
     * @param   Feed|PropelObjectCollection $feed  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CategoryQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFeed($feed, $comparison = null)
    {
        if ($feed instanceof Feed) {
            return $this
                ->addUsingAlias(CategoryPeer::ID, $feed->getCategoryId(), $comparison);
        } elseif ($feed instanceof PropelObjectCollection) {
            return $this
                ->useFeedQuery()
                ->filterByPrimaryKeys($feed->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFeed() only accepts arguments of type Feed or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Feed relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CategoryQuery The current query, for fluid interface
     */
    public function joinFeed($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Feed');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Feed');
        }

        return $this;
    }

    /**
     * Use the Feed relation Feed object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   FeedQuery A secondary query class using the current class as primary query
     */
    public function useFeedQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFeed($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Feed', 'FeedQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Category $category Object to remove from the list of results
     *
     * @return CategoryQuery The current query, for fluid interface
     */
    public function prune($category = null)
    {
        if ($category) {
            $this->addUsingAlias(CategoryPeer::ID, $category->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
