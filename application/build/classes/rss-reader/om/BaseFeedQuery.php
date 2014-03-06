<?php


/**
 * Base class that represents a query for the 'rss_feed' table.
 *
 *
 *
 * @method FeedQuery orderById($order = Criteria::ASC) Order by the id column
 * @method FeedQuery orderByLink($order = Criteria::ASC) Order by the link column
 * @method FeedQuery orderByBaseLink($order = Criteria::ASC) Order by the base_link column
 * @method FeedQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method FeedQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method FeedQuery orderByUpdated($order = Criteria::ASC) Order by the updated column
 * @method FeedQuery orderByCategoryId($order = Criteria::ASC) Order by the category_id column
 * @method FeedQuery orderByValid($order = Criteria::ASC) Order by the valid column
 * @method FeedQuery orderByViewframe($order = Criteria::ASC) Order by the viewframe column
 * @method FeedQuery orderBycatOrder($order = Criteria::ASC) Order by the cat_order column
 *
 * @method FeedQuery groupById() Group by the id column
 * @method FeedQuery groupByLink() Group by the link column
 * @method FeedQuery groupByBaseLink() Group by the base_link column
 * @method FeedQuery groupByTitle() Group by the title column
 * @method FeedQuery groupByDescription() Group by the description column
 * @method FeedQuery groupByUpdated() Group by the updated column
 * @method FeedQuery groupByCategoryId() Group by the category_id column
 * @method FeedQuery groupByValid() Group by the valid column
 * @method FeedQuery groupByViewframe() Group by the viewframe column
 * @method FeedQuery groupBycatOrder() Group by the cat_order column
 *
 * @method FeedQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method FeedQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method FeedQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method FeedQuery leftJoinCategory($relationAlias = null) Adds a LEFT JOIN clause to the query using the Category relation
 * @method FeedQuery rightJoinCategory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Category relation
 * @method FeedQuery innerJoinCategory($relationAlias = null) Adds a INNER JOIN clause to the query using the Category relation
 *
 * @method FeedQuery leftJoinEntry($relationAlias = null) Adds a LEFT JOIN clause to the query using the Entry relation
 * @method FeedQuery rightJoinEntry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Entry relation
 * @method FeedQuery innerJoinEntry($relationAlias = null) Adds a INNER JOIN clause to the query using the Entry relation
 *
 * @method Feed findOne(PropelPDO $con = null) Return the first Feed matching the query
 * @method Feed findOneOrCreate(PropelPDO $con = null) Return the first Feed matching the query, or a new Feed object populated from the query conditions when no match is found
 *
 * @method Feed findOneByLink(string $link) Return the first Feed filtered by the link column
 * @method Feed findOneByBaseLink(string $base_link) Return the first Feed filtered by the base_link column
 * @method Feed findOneByTitle(string $title) Return the first Feed filtered by the title column
 * @method Feed findOneByDescription(string $description) Return the first Feed filtered by the description column
 * @method Feed findOneByUpdated(string $updated) Return the first Feed filtered by the updated column
 * @method Feed findOneByCategoryId(int $category_id) Return the first Feed filtered by the category_id column
 * @method Feed findOneByValid(boolean $valid) Return the first Feed filtered by the valid column
 * @method Feed findOneByViewframe(boolean $viewframe) Return the first Feed filtered by the viewframe column
 * @method Feed findOneBycatOrder(int $cat_order) Return the first Feed filtered by the cat_order column
 *
 * @method array findById(int $id) Return Feed objects filtered by the id column
 * @method array findByLink(string $link) Return Feed objects filtered by the link column
 * @method array findByBaseLink(string $base_link) Return Feed objects filtered by the base_link column
 * @method array findByTitle(string $title) Return Feed objects filtered by the title column
 * @method array findByDescription(string $description) Return Feed objects filtered by the description column
 * @method array findByUpdated(string $updated) Return Feed objects filtered by the updated column
 * @method array findByCategoryId(int $category_id) Return Feed objects filtered by the category_id column
 * @method array findByValid(boolean $valid) Return Feed objects filtered by the valid column
 * @method array findByViewframe(boolean $viewframe) Return Feed objects filtered by the viewframe column
 * @method array findBycatOrder(int $cat_order) Return Feed objects filtered by the cat_order column
 *
 * @package    propel.generator.rss-reader.om
 */
abstract class BaseFeedQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseFeedQuery object.
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
            $modelName = 'Feed';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new FeedQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   FeedQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return FeedQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof FeedQuery) {
            return $criteria;
        }
        $query = new FeedQuery(null, null, $modelAlias);

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
     * @return   Feed|Feed[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = FeedPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(FeedPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Feed A model object, or null if the key is not found
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
     * @return                 Feed A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `link`, `base_link`, `title`, `description`, `updated`, `category_id`, `valid`, `viewframe`, `cat_order` FROM `rss_feed` WHERE `id` = :p0';
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
            $obj = new Feed();
            $obj->hydrate($row);
            FeedPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Feed|Feed[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Feed[]|mixed the list of results, formatted by the current formatter
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
     * @return FeedQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(FeedPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return FeedQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(FeedPeer::ID, $keys, Criteria::IN);
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
     * @return FeedQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(FeedPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(FeedPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FeedPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the link column
     *
     * Example usage:
     * <code>
     * $query->filterByLink('fooValue');   // WHERE link = 'fooValue'
     * $query->filterByLink('%fooValue%'); // WHERE link LIKE '%fooValue%'
     * </code>
     *
     * @param     string $link The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FeedQuery The current query, for fluid interface
     */
    public function filterByLink($link = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($link)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $link)) {
                $link = str_replace('*', '%', $link);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FeedPeer::LINK, $link, $comparison);
    }

    /**
     * Filter the query on the base_link column
     *
     * Example usage:
     * <code>
     * $query->filterByBaseLink('fooValue');   // WHERE base_link = 'fooValue'
     * $query->filterByBaseLink('%fooValue%'); // WHERE base_link LIKE '%fooValue%'
     * </code>
     *
     * @param     string $baseLink The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FeedQuery The current query, for fluid interface
     */
    public function filterByBaseLink($baseLink = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($baseLink)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $baseLink)) {
                $baseLink = str_replace('*', '%', $baseLink);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FeedPeer::BASE_LINK, $baseLink, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%'); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FeedQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $title)) {
                $title = str_replace('*', '%', $title);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FeedPeer::TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE description = 'fooValue'
     * $query->filterByDescription('%fooValue%'); // WHERE description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FeedQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $description)) {
                $description = str_replace('*', '%', $description);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FeedPeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdated('2011-03-14'); // WHERE updated = '2011-03-14'
     * $query->filterByUpdated('now'); // WHERE updated = '2011-03-14'
     * $query->filterByUpdated(array('max' => 'yesterday')); // WHERE updated < '2011-03-13'
     * </code>
     *
     * @param     mixed $updated The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FeedQuery The current query, for fluid interface
     */
    public function filterByUpdated($updated = null, $comparison = null)
    {
        if (is_array($updated)) {
            $useMinMax = false;
            if (isset($updated['min'])) {
                $this->addUsingAlias(FeedPeer::UPDATED, $updated['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updated['max'])) {
                $this->addUsingAlias(FeedPeer::UPDATED, $updated['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FeedPeer::UPDATED, $updated, $comparison);
    }

    /**
     * Filter the query on the category_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCategoryId(1234); // WHERE category_id = 1234
     * $query->filterByCategoryId(array(12, 34)); // WHERE category_id IN (12, 34)
     * $query->filterByCategoryId(array('min' => 12)); // WHERE category_id >= 12
     * $query->filterByCategoryId(array('max' => 12)); // WHERE category_id <= 12
     * </code>
     *
     * @see       filterByCategory()
     *
     * @param     mixed $categoryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FeedQuery The current query, for fluid interface
     */
    public function filterByCategoryId($categoryId = null, $comparison = null)
    {
        if (is_array($categoryId)) {
            $useMinMax = false;
            if (isset($categoryId['min'])) {
                $this->addUsingAlias(FeedPeer::CATEGORY_ID, $categoryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($categoryId['max'])) {
                $this->addUsingAlias(FeedPeer::CATEGORY_ID, $categoryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FeedPeer::CATEGORY_ID, $categoryId, $comparison);
    }

    /**
     * Filter the query on the valid column
     *
     * Example usage:
     * <code>
     * $query->filterByValid(true); // WHERE valid = true
     * $query->filterByValid('yes'); // WHERE valid = true
     * </code>
     *
     * @param     boolean|string $valid The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FeedQuery The current query, for fluid interface
     */
    public function filterByValid($valid = null, $comparison = null)
    {
        if (is_string($valid)) {
            $valid = in_array(strtolower($valid), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(FeedPeer::VALID, $valid, $comparison);
    }

    /**
     * Filter the query on the viewframe column
     *
     * Example usage:
     * <code>
     * $query->filterByViewframe(true); // WHERE viewframe = true
     * $query->filterByViewframe('yes'); // WHERE viewframe = true
     * </code>
     *
     * @param     boolean|string $viewframe The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FeedQuery The current query, for fluid interface
     */
    public function filterByViewframe($viewframe = null, $comparison = null)
    {
        if (is_string($viewframe)) {
            $viewframe = in_array(strtolower($viewframe), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(FeedPeer::VIEWFRAME, $viewframe, $comparison);
    }

    /**
     * Filter the query on the cat_order column
     *
     * Example usage:
     * <code>
     * $query->filterBycatOrder(1234); // WHERE cat_order = 1234
     * $query->filterBycatOrder(array(12, 34)); // WHERE cat_order IN (12, 34)
     * $query->filterBycatOrder(array('min' => 12)); // WHERE cat_order >= 12
     * $query->filterBycatOrder(array('max' => 12)); // WHERE cat_order <= 12
     * </code>
     *
     * @param     mixed $catOrder The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FeedQuery The current query, for fluid interface
     */
    public function filterBycatOrder($catOrder = null, $comparison = null)
    {
        if (is_array($catOrder)) {
            $useMinMax = false;
            if (isset($catOrder['min'])) {
                $this->addUsingAlias(FeedPeer::CAT_ORDER, $catOrder['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($catOrder['max'])) {
                $this->addUsingAlias(FeedPeer::CAT_ORDER, $catOrder['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FeedPeer::CAT_ORDER, $catOrder, $comparison);
    }

    /**
     * Filter the query by a related Category object
     *
     * @param   Category|PropelObjectCollection $category The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FeedQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCategory($category, $comparison = null)
    {
        if ($category instanceof Category) {
            return $this
                ->addUsingAlias(FeedPeer::CATEGORY_ID, $category->getId(), $comparison);
        } elseif ($category instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FeedPeer::CATEGORY_ID, $category->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCategory() only accepts arguments of type Category or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Category relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FeedQuery The current query, for fluid interface
     */
    public function joinCategory($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Category');

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
            $this->addJoinObject($join, 'Category');
        }

        return $this;
    }

    /**
     * Use the Category relation Category object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   CategoryQuery A secondary query class using the current class as primary query
     */
    public function useCategoryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCategory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Category', 'CategoryQuery');
    }

    /**
     * Filter the query by a related Entry object
     *
     * @param   Entry|PropelObjectCollection $entry  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FeedQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEntry($entry, $comparison = null)
    {
        if ($entry instanceof Entry) {
            return $this
                ->addUsingAlias(FeedPeer::ID, $entry->getFeedId(), $comparison);
        } elseif ($entry instanceof PropelObjectCollection) {
            return $this
                ->useEntryQuery()
                ->filterByPrimaryKeys($entry->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEntry() only accepts arguments of type Entry or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Entry relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FeedQuery The current query, for fluid interface
     */
    public function joinEntry($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Entry');

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
            $this->addJoinObject($join, 'Entry');
        }

        return $this;
    }

    /**
     * Use the Entry relation Entry object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   EntryQuery A secondary query class using the current class as primary query
     */
    public function useEntryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEntry($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Entry', 'EntryQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Feed $feed Object to remove from the list of results
     *
     * @return FeedQuery The current query, for fluid interface
     */
    public function prune($feed = null)
    {
        if ($feed) {
            $this->addUsingAlias(FeedPeer::ID, $feed->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
