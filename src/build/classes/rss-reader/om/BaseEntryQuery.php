<?php


/**
 * Base class that represents a query for the 'rss_entry' table.
 *
 *
 *
 * @method EntryQuery orderById($order = Criteria::ASC) Order by the id column
 * @method EntryQuery orderByPublished($order = Criteria::ASC) Order by the published column
 * @method EntryQuery orderByUpdated($order = Criteria::ASC) Order by the updated column
 * @method EntryQuery orderByLink($order = Criteria::ASC) Order by the link column
 * @method EntryQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method EntryQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method EntryQuery orderByAuthor($order = Criteria::ASC) Order by the author column
 * @method EntryQuery orderByRead($order = Criteria::ASC) Order by the read column
 * @method EntryQuery orderByContent($order = Criteria::ASC) Order by the content column
 * @method EntryQuery orderByFeedId($order = Criteria::ASC) Order by the feed_id column
 * @method EntryQuery orderByFavourite($order = Criteria::ASC) Order by the favourite column
 * @method EntryQuery orderByToRead($order = Criteria::ASC) Order by the to_read column
 *
 * @method EntryQuery groupById() Group by the id column
 * @method EntryQuery groupByPublished() Group by the published column
 * @method EntryQuery groupByUpdated() Group by the updated column
 * @method EntryQuery groupByLink() Group by the link column
 * @method EntryQuery groupByTitle() Group by the title column
 * @method EntryQuery groupByDescription() Group by the description column
 * @method EntryQuery groupByAuthor() Group by the author column
 * @method EntryQuery groupByRead() Group by the read column
 * @method EntryQuery groupByContent() Group by the content column
 * @method EntryQuery groupByFeedId() Group by the feed_id column
 * @method EntryQuery groupByFavourite() Group by the favourite column
 * @method EntryQuery groupByToRead() Group by the to_read column
 *
 * @method EntryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method EntryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method EntryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method EntryQuery leftJoinFeed($relationAlias = null) Adds a LEFT JOIN clause to the query using the Feed relation
 * @method EntryQuery rightJoinFeed($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Feed relation
 * @method EntryQuery innerJoinFeed($relationAlias = null) Adds a INNER JOIN clause to the query using the Feed relation
 *
 * @method Entry findOne(PropelPDO $con = null) Return the first Entry matching the query
 * @method Entry findOneOrCreate(PropelPDO $con = null) Return the first Entry matching the query, or a new Entry object populated from the query conditions when no match is found
 *
 * @method Entry findOneByPublished(string $published) Return the first Entry filtered by the published column
 * @method Entry findOneByUpdated(string $updated) Return the first Entry filtered by the updated column
 * @method Entry findOneByLink(string $link) Return the first Entry filtered by the link column
 * @method Entry findOneByTitle(string $title) Return the first Entry filtered by the title column
 * @method Entry findOneByDescription(string $description) Return the first Entry filtered by the description column
 * @method Entry findOneByAuthor(string $author) Return the first Entry filtered by the author column
 * @method Entry findOneByRead(int $read) Return the first Entry filtered by the read column
 * @method Entry findOneByContent(string $content) Return the first Entry filtered by the content column
 * @method Entry findOneByFeedId(int $feed_id) Return the first Entry filtered by the feed_id column
 * @method Entry findOneByFavourite(int $favourite) Return the first Entry filtered by the favourite column
 * @method Entry findOneByToRead(int $to_read) Return the first Entry filtered by the to_read column
 *
 * @method array findById(int $id) Return Entry objects filtered by the id column
 * @method array findByPublished(string $published) Return Entry objects filtered by the published column
 * @method array findByUpdated(string $updated) Return Entry objects filtered by the updated column
 * @method array findByLink(string $link) Return Entry objects filtered by the link column
 * @method array findByTitle(string $title) Return Entry objects filtered by the title column
 * @method array findByDescription(string $description) Return Entry objects filtered by the description column
 * @method array findByAuthor(string $author) Return Entry objects filtered by the author column
 * @method array findByRead(int $read) Return Entry objects filtered by the read column
 * @method array findByContent(string $content) Return Entry objects filtered by the content column
 * @method array findByFeedId(int $feed_id) Return Entry objects filtered by the feed_id column
 * @method array findByFavourite(int $favourite) Return Entry objects filtered by the favourite column
 * @method array findByToRead(int $to_read) Return Entry objects filtered by the to_read column
 *
 * @package    propel.generator.rss-reader.om
 */
abstract class BaseEntryQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseEntryQuery object.
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
            $modelName = 'Entry';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new EntryQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   EntryQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return EntryQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof EntryQuery) {
            return $criteria;
        }
        $query = new EntryQuery(null, null, $modelAlias);

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
     * @return   Entry|Entry[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = EntryPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(EntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Entry A model object, or null if the key is not found
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
     * @return                 Entry A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `published`, `updated`, `link`, `title`, `description`, `author`, `read`, `content`, `feed_id`, `favourite`, `to_read` FROM `rss_entry` WHERE `id` = :p0';
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
            $obj = new Entry();
            $obj->hydrate($row);
            EntryPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Entry|Entry[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Entry[]|mixed the list of results, formatted by the current formatter
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
     * @return EntryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(EntryPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return EntryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(EntryPeer::ID, $keys, Criteria::IN);
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
     * @return EntryQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(EntryPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(EntryPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EntryPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the published column
     *
     * Example usage:
     * <code>
     * $query->filterByPublished('2011-03-14'); // WHERE published = '2011-03-14'
     * $query->filterByPublished('now'); // WHERE published = '2011-03-14'
     * $query->filterByPublished(array('max' => 'yesterday')); // WHERE published < '2011-03-13'
     * </code>
     *
     * @param     mixed $published The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EntryQuery The current query, for fluid interface
     */
    public function filterByPublished($published = null, $comparison = null)
    {
        if (is_array($published)) {
            $useMinMax = false;
            if (isset($published['min'])) {
                $this->addUsingAlias(EntryPeer::PUBLISHED, $published['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($published['max'])) {
                $this->addUsingAlias(EntryPeer::PUBLISHED, $published['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EntryPeer::PUBLISHED, $published, $comparison);
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
     * @return EntryQuery The current query, for fluid interface
     */
    public function filterByUpdated($updated = null, $comparison = null)
    {
        if (is_array($updated)) {
            $useMinMax = false;
            if (isset($updated['min'])) {
                $this->addUsingAlias(EntryPeer::UPDATED, $updated['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updated['max'])) {
                $this->addUsingAlias(EntryPeer::UPDATED, $updated['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EntryPeer::UPDATED, $updated, $comparison);
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
     * @return EntryQuery The current query, for fluid interface
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

        return $this->addUsingAlias(EntryPeer::LINK, $link, $comparison);
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
     * @return EntryQuery The current query, for fluid interface
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

        return $this->addUsingAlias(EntryPeer::TITLE, $title, $comparison);
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
     * @return EntryQuery The current query, for fluid interface
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

        return $this->addUsingAlias(EntryPeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the author column
     *
     * Example usage:
     * <code>
     * $query->filterByAuthor('fooValue');   // WHERE author = 'fooValue'
     * $query->filterByAuthor('%fooValue%'); // WHERE author LIKE '%fooValue%'
     * </code>
     *
     * @param     string $author The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EntryQuery The current query, for fluid interface
     */
    public function filterByAuthor($author = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($author)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $author)) {
                $author = str_replace('*', '%', $author);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EntryPeer::AUTHOR, $author, $comparison);
    }

    /**
     * Filter the query on the read column
     *
     * Example usage:
     * <code>
     * $query->filterByRead(1234); // WHERE read = 1234
     * $query->filterByRead(array(12, 34)); // WHERE read IN (12, 34)
     * $query->filterByRead(array('min' => 12)); // WHERE read >= 12
     * $query->filterByRead(array('max' => 12)); // WHERE read <= 12
     * </code>
     *
     * @param     mixed $read The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EntryQuery The current query, for fluid interface
     */
    public function filterByRead($read = null, $comparison = null)
    {
        if (is_array($read)) {
            $useMinMax = false;
            if (isset($read['min'])) {
                $this->addUsingAlias(EntryPeer::READ, $read['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($read['max'])) {
                $this->addUsingAlias(EntryPeer::READ, $read['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EntryPeer::READ, $read, $comparison);
    }

    /**
     * Filter the query on the content column
     *
     * Example usage:
     * <code>
     * $query->filterByContent('fooValue');   // WHERE content = 'fooValue'
     * $query->filterByContent('%fooValue%'); // WHERE content LIKE '%fooValue%'
     * </code>
     *
     * @param     string $content The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EntryQuery The current query, for fluid interface
     */
    public function filterByContent($content = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($content)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $content)) {
                $content = str_replace('*', '%', $content);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EntryPeer::CONTENT, $content, $comparison);
    }

    /**
     * Filter the query on the feed_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFeedId(1234); // WHERE feed_id = 1234
     * $query->filterByFeedId(array(12, 34)); // WHERE feed_id IN (12, 34)
     * $query->filterByFeedId(array('min' => 12)); // WHERE feed_id >= 12
     * $query->filterByFeedId(array('max' => 12)); // WHERE feed_id <= 12
     * </code>
     *
     * @see       filterByFeed()
     *
     * @param     mixed $feedId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EntryQuery The current query, for fluid interface
     */
    public function filterByFeedId($feedId = null, $comparison = null)
    {
        if (is_array($feedId)) {
            $useMinMax = false;
            if (isset($feedId['min'])) {
                $this->addUsingAlias(EntryPeer::FEED_ID, $feedId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($feedId['max'])) {
                $this->addUsingAlias(EntryPeer::FEED_ID, $feedId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EntryPeer::FEED_ID, $feedId, $comparison);
    }

    /**
     * Filter the query on the favourite column
     *
     * Example usage:
     * <code>
     * $query->filterByFavourite(1234); // WHERE favourite = 1234
     * $query->filterByFavourite(array(12, 34)); // WHERE favourite IN (12, 34)
     * $query->filterByFavourite(array('min' => 12)); // WHERE favourite >= 12
     * $query->filterByFavourite(array('max' => 12)); // WHERE favourite <= 12
     * </code>
     *
     * @param     mixed $favourite The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EntryQuery The current query, for fluid interface
     */
    public function filterByFavourite($favourite = null, $comparison = null)
    {
        if (is_array($favourite)) {
            $useMinMax = false;
            if (isset($favourite['min'])) {
                $this->addUsingAlias(EntryPeer::FAVOURITE, $favourite['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($favourite['max'])) {
                $this->addUsingAlias(EntryPeer::FAVOURITE, $favourite['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EntryPeer::FAVOURITE, $favourite, $comparison);
    }

    /**
     * Filter the query on the to_read column
     *
     * Example usage:
     * <code>
     * $query->filterByToRead(1234); // WHERE to_read = 1234
     * $query->filterByToRead(array(12, 34)); // WHERE to_read IN (12, 34)
     * $query->filterByToRead(array('min' => 12)); // WHERE to_read >= 12
     * $query->filterByToRead(array('max' => 12)); // WHERE to_read <= 12
     * </code>
     *
     * @param     mixed $toRead The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EntryQuery The current query, for fluid interface
     */
    public function filterByToRead($toRead = null, $comparison = null)
    {
        if (is_array($toRead)) {
            $useMinMax = false;
            if (isset($toRead['min'])) {
                $this->addUsingAlias(EntryPeer::TO_READ, $toRead['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($toRead['max'])) {
                $this->addUsingAlias(EntryPeer::TO_READ, $toRead['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EntryPeer::TO_READ, $toRead, $comparison);
    }

    /**
     * Filter the query by a related Feed object
     *
     * @param   Feed|PropelObjectCollection $feed The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EntryQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFeed($feed, $comparison = null)
    {
        if ($feed instanceof Feed) {
            return $this
                ->addUsingAlias(EntryPeer::FEED_ID, $feed->getId(), $comparison);
        } elseif ($feed instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EntryPeer::FEED_ID, $feed->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return EntryQuery The current query, for fluid interface
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
     * @param   Entry $entry Object to remove from the list of results
     *
     * @return EntryQuery The current query, for fluid interface
     */
    public function prune($entry = null)
    {
        if ($entry) {
            $this->addUsingAlias(EntryPeer::ID, $entry->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
