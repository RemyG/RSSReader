<?php


/**
 * Base class that represents a row from the 'rss_category' table.
 *
 *
 *
 * @package    propel.generator.rss-reader.om
 */
abstract class BaseCategory extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'CategoryPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        CategoryPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the parent_category_id field.
     * @var        int
     */
    protected $parent_category_id;

    /**
     * @var        Category
     */
    protected $aParentCategory;

    /**
     * @var        PropelObjectCollection|Category[] Collection to store aggregation of Category objects.
     */
    protected $collChildrenCategorys;
    protected $collChildrenCategorysPartial;

    /**
     * @var        PropelObjectCollection|Feed[] Collection to store aggregation of Feed objects.
     */
    protected $collFeeds;
    protected $collFeedsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * Flag to prevent endless clearAllReferences($deep=true) loop, if this object is referenced
     * @var        boolean
     */
    protected $alreadyInClearAllReferencesDeep = false;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $childrenCategorysScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $feedsScheduledForDeletion = null;

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {

        return $this->name;
    }

    /**
     * Get the [parent_category_id] column value.
     *
     * @return int
     */
    public function getParentCategoryId()
    {

        return $this->parent_category_id;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return Category The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = CategoryPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return Category The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = CategoryPeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [parent_category_id] column.
     *
     * @param int $v new value
     * @return Category The current object (for fluent API support)
     */
    public function setParentCategoryId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->parent_category_id !== $v) {
            $this->parent_category_id = $v;
            $this->modifiedColumns[] = CategoryPeer::PARENT_CATEGORY_ID;
        }

        if ($this->aParentCategory !== null && $this->aParentCategory->getId() !== $v) {
            $this->aParentCategory = null;
        }


        return $this;
    } // setParentCategoryId()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return true
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which resultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->name = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->parent_category_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 3; // 3 = CategoryPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Category object", $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {

        if ($this->aParentCategory !== null && $this->parent_category_id !== $this->aParentCategory->getId()) {
            $this->aParentCategory = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(CategoryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = CategoryPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aParentCategory = null;
            $this->collChildrenCategorys = null;

            $this->collFeeds = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(CategoryPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = CategoryQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(CategoryPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                CategoryPeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aParentCategory !== null) {
                if ($this->aParentCategory->isModified() || $this->aParentCategory->isNew()) {
                    $affectedRows += $this->aParentCategory->save($con);
                }
                $this->setParentCategory($this->aParentCategory);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->childrenCategorysScheduledForDeletion !== null) {
                if (!$this->childrenCategorysScheduledForDeletion->isEmpty()) {
                    foreach ($this->childrenCategorysScheduledForDeletion as $childrenCategory) {
                        // need to save related object because we set the relation to null
                        $childrenCategory->save($con);
                    }
                    $this->childrenCategorysScheduledForDeletion = null;
                }
            }

            if ($this->collChildrenCategorys !== null) {
                foreach ($this->collChildrenCategorys as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->feedsScheduledForDeletion !== null) {
                if (!$this->feedsScheduledForDeletion->isEmpty()) {
                    foreach ($this->feedsScheduledForDeletion as $feed) {
                        // need to save related object because we set the relation to null
                        $feed->save($con);
                    }
                    $this->feedsScheduledForDeletion = null;
                }
            }

            if ($this->collFeeds !== null) {
                foreach ($this->collFeeds as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[] = CategoryPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . CategoryPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CategoryPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(CategoryPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(CategoryPeer::PARENT_CATEGORY_ID)) {
            $modifiedColumns[':p' . $index++]  = '`parent_category_id`';
        }

        $sql = sprintf(
            'INSERT INTO `rss_category` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`name`':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`parent_category_id`':
                        $stmt->bindValue($identifier, $this->parent_category_id, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        }

        $this->validationFailures = $res;

        return false;
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggregated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objects otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            // We call the validate method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aParentCategory !== null) {
                if (!$this->aParentCategory->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aParentCategory->getValidationFailures());
                }
            }


            if (($retval = CategoryPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collChildrenCategorys !== null) {
                    foreach ($this->collChildrenCategorys as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collFeeds !== null) {
                    foreach ($this->collFeeds as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }


            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = CategoryPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getName();
                break;
            case 2:
                return $this->getParentCategoryId();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['Category'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Category'][$this->getPrimaryKey()] = true;
        $keys = CategoryPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getParentCategoryId(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aParentCategory) {
                $result['ParentCategory'] = $this->aParentCategory->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collChildrenCategorys) {
                $result['ChildrenCategorys'] = $this->collChildrenCategorys->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collFeeds) {
                $result['Feeds'] = $this->collFeeds->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = CategoryPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setName($value);
                break;
            case 2:
                $this->setParentCategoryId($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = CategoryPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setParentCategoryId($arr[$keys[2]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(CategoryPeer::DATABASE_NAME);

        if ($this->isColumnModified(CategoryPeer::ID)) $criteria->add(CategoryPeer::ID, $this->id);
        if ($this->isColumnModified(CategoryPeer::NAME)) $criteria->add(CategoryPeer::NAME, $this->name);
        if ($this->isColumnModified(CategoryPeer::PARENT_CATEGORY_ID)) $criteria->add(CategoryPeer::PARENT_CATEGORY_ID, $this->parent_category_id);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(CategoryPeer::DATABASE_NAME);
        $criteria->add(CategoryPeer::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of Category (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setParentCategoryId($this->getParentCategoryId());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getChildrenCategorys() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addChildrenCategory($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getFeeds() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFeed($relObj->copy($deepCopy));
                }
            }

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return Category Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return CategoryPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new CategoryPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Category object.
     *
     * @param   Category $v
     * @return Category The current object (for fluent API support)
     * @throws PropelException
     */
    public function setParentCategory(Category $v = null)
    {
        if ($v === null) {
            $this->setParentCategoryId(NULL);
        } else {
            $this->setParentCategoryId($v->getId());
        }

        $this->aParentCategory = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Category object, it will not be re-added.
        if ($v !== null) {
            $v->addChildrenCategory($this);
        }


        return $this;
    }


    /**
     * Get the associated Category object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Category The associated Category object.
     * @throws PropelException
     */
    public function getParentCategory(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aParentCategory === null && ($this->parent_category_id !== null) && $doQuery) {
            $this->aParentCategory = CategoryQuery::create()->findPk($this->parent_category_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aParentCategory->addChildrenCategorys($this);
             */
        }

        return $this->aParentCategory;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('ChildrenCategory' == $relationName) {
            $this->initChildrenCategorys();
        }
        if ('Feed' == $relationName) {
            $this->initFeeds();
        }
    }

    /**
     * Clears out the collChildrenCategorys collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Category The current object (for fluent API support)
     * @see        addChildrenCategorys()
     */
    public function clearChildrenCategorys()
    {
        $this->collChildrenCategorys = null; // important to set this to null since that means it is uninitialized
        $this->collChildrenCategorysPartial = null;

        return $this;
    }

    /**
     * reset is the collChildrenCategorys collection loaded partially
     *
     * @return void
     */
    public function resetPartialChildrenCategorys($v = true)
    {
        $this->collChildrenCategorysPartial = $v;
    }

    /**
     * Initializes the collChildrenCategorys collection.
     *
     * By default this just sets the collChildrenCategorys collection to an empty array (like clearcollChildrenCategorys());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initChildrenCategorys($overrideExisting = true)
    {
        if (null !== $this->collChildrenCategorys && !$overrideExisting) {
            return;
        }
        $this->collChildrenCategorys = new PropelObjectCollection();
        $this->collChildrenCategorys->setModel('Category');
    }

    /**
     * Gets an array of Category objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Category is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Category[] List of Category objects
     * @throws PropelException
     */
    public function getChildrenCategorys($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collChildrenCategorysPartial && !$this->isNew();
        if (null === $this->collChildrenCategorys || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collChildrenCategorys) {
                // return empty collection
                $this->initChildrenCategorys();
            } else {
                $collChildrenCategorys = CategoryQuery::create(null, $criteria)
                    ->filterByParentCategory($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collChildrenCategorysPartial && count($collChildrenCategorys)) {
                      $this->initChildrenCategorys(false);

                      foreach ($collChildrenCategorys as $obj) {
                        if (false == $this->collChildrenCategorys->contains($obj)) {
                          $this->collChildrenCategorys->append($obj);
                        }
                      }

                      $this->collChildrenCategorysPartial = true;
                    }

                    $collChildrenCategorys->getInternalIterator()->rewind();

                    return $collChildrenCategorys;
                }

                if ($partial && $this->collChildrenCategorys) {
                    foreach ($this->collChildrenCategorys as $obj) {
                        if ($obj->isNew()) {
                            $collChildrenCategorys[] = $obj;
                        }
                    }
                }

                $this->collChildrenCategorys = $collChildrenCategorys;
                $this->collChildrenCategorysPartial = false;
            }
        }

        return $this->collChildrenCategorys;
    }

    /**
     * Sets a collection of ChildrenCategory objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $childrenCategorys A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Category The current object (for fluent API support)
     */
    public function setChildrenCategorys(PropelCollection $childrenCategorys, PropelPDO $con = null)
    {
        $childrenCategorysToDelete = $this->getChildrenCategorys(new Criteria(), $con)->diff($childrenCategorys);


        $this->childrenCategorysScheduledForDeletion = $childrenCategorysToDelete;

        foreach ($childrenCategorysToDelete as $childrenCategoryRemoved) {
            $childrenCategoryRemoved->setParentCategory(null);
        }

        $this->collChildrenCategorys = null;
        foreach ($childrenCategorys as $childrenCategory) {
            $this->addChildrenCategory($childrenCategory);
        }

        $this->collChildrenCategorys = $childrenCategorys;
        $this->collChildrenCategorysPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Category objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Category objects.
     * @throws PropelException
     */
    public function countChildrenCategorys(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collChildrenCategorysPartial && !$this->isNew();
        if (null === $this->collChildrenCategorys || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collChildrenCategorys) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getChildrenCategorys());
            }
            $query = CategoryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByParentCategory($this)
                ->count($con);
        }

        return count($this->collChildrenCategorys);
    }

    /**
     * Method called to associate a Category object to this object
     * through the Category foreign key attribute.
     *
     * @param   Category $l Category
     * @return Category The current object (for fluent API support)
     */
    public function addChildrenCategory(Category $l)
    {
        if ($this->collChildrenCategorys === null) {
            $this->initChildrenCategorys();
            $this->collChildrenCategorysPartial = true;
        }
        if (!in_array($l, $this->collChildrenCategorys->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddChildrenCategory($l);
        }

        return $this;
    }

    /**
     * @param	ChildrenCategory $childrenCategory The childrenCategory object to add.
     */
    protected function doAddChildrenCategory($childrenCategory)
    {
        $this->collChildrenCategorys[]= $childrenCategory;
        $childrenCategory->setParentCategory($this);
    }

    /**
     * @param	ChildrenCategory $childrenCategory The childrenCategory object to remove.
     * @return Category The current object (for fluent API support)
     */
    public function removeChildrenCategory($childrenCategory)
    {
        if ($this->getChildrenCategorys()->contains($childrenCategory)) {
            $this->collChildrenCategorys->remove($this->collChildrenCategorys->search($childrenCategory));
            if (null === $this->childrenCategorysScheduledForDeletion) {
                $this->childrenCategorysScheduledForDeletion = clone $this->collChildrenCategorys;
                $this->childrenCategorysScheduledForDeletion->clear();
            }
            $this->childrenCategorysScheduledForDeletion[]= $childrenCategory;
            $childrenCategory->setParentCategory(null);
        }

        return $this;
    }

    /**
     * Clears out the collFeeds collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Category The current object (for fluent API support)
     * @see        addFeeds()
     */
    public function clearFeeds()
    {
        $this->collFeeds = null; // important to set this to null since that means it is uninitialized
        $this->collFeedsPartial = null;

        return $this;
    }

    /**
     * reset is the collFeeds collection loaded partially
     *
     * @return void
     */
    public function resetPartialFeeds($v = true)
    {
        $this->collFeedsPartial = $v;
    }

    /**
     * Initializes the collFeeds collection.
     *
     * By default this just sets the collFeeds collection to an empty array (like clearcollFeeds());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFeeds($overrideExisting = true)
    {
        if (null !== $this->collFeeds && !$overrideExisting) {
            return;
        }
        $this->collFeeds = new PropelObjectCollection();
        $this->collFeeds->setModel('Feed');
    }

    /**
     * Gets an array of Feed objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Category is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Feed[] List of Feed objects
     * @throws PropelException
     */
    public function getFeeds($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collFeedsPartial && !$this->isNew();
        if (null === $this->collFeeds || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFeeds) {
                // return empty collection
                $this->initFeeds();
            } else {
                $collFeeds = FeedQuery::create(null, $criteria)
                    ->filterByCategory($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collFeedsPartial && count($collFeeds)) {
                      $this->initFeeds(false);

                      foreach ($collFeeds as $obj) {
                        if (false == $this->collFeeds->contains($obj)) {
                          $this->collFeeds->append($obj);
                        }
                      }

                      $this->collFeedsPartial = true;
                    }

                    $collFeeds->getInternalIterator()->rewind();

                    return $collFeeds;
                }

                if ($partial && $this->collFeeds) {
                    foreach ($this->collFeeds as $obj) {
                        if ($obj->isNew()) {
                            $collFeeds[] = $obj;
                        }
                    }
                }

                $this->collFeeds = $collFeeds;
                $this->collFeedsPartial = false;
            }
        }

        return $this->collFeeds;
    }

    /**
     * Sets a collection of Feed objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $feeds A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Category The current object (for fluent API support)
     */
    public function setFeeds(PropelCollection $feeds, PropelPDO $con = null)
    {
        $feedsToDelete = $this->getFeeds(new Criteria(), $con)->diff($feeds);


        $this->feedsScheduledForDeletion = $feedsToDelete;

        foreach ($feedsToDelete as $feedRemoved) {
            $feedRemoved->setCategory(null);
        }

        $this->collFeeds = null;
        foreach ($feeds as $feed) {
            $this->addFeed($feed);
        }

        $this->collFeeds = $feeds;
        $this->collFeedsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Feed objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Feed objects.
     * @throws PropelException
     */
    public function countFeeds(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collFeedsPartial && !$this->isNew();
        if (null === $this->collFeeds || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFeeds) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getFeeds());
            }
            $query = FeedQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCategory($this)
                ->count($con);
        }

        return count($this->collFeeds);
    }

    /**
     * Method called to associate a Feed object to this object
     * through the Feed foreign key attribute.
     *
     * @param   Feed $l Feed
     * @return Category The current object (for fluent API support)
     */
    public function addFeed(Feed $l)
    {
        if ($this->collFeeds === null) {
            $this->initFeeds();
            $this->collFeedsPartial = true;
        }
        if (!in_array($l, $this->collFeeds->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddFeed($l);
        }

        return $this;
    }

    /**
     * @param	Feed $feed The feed object to add.
     */
    protected function doAddFeed($feed)
    {
        $this->collFeeds[]= $feed;
        $feed->setCategory($this);
    }

    /**
     * @param	Feed $feed The feed object to remove.
     * @return Category The current object (for fluent API support)
     */
    public function removeFeed($feed)
    {
        if ($this->getFeeds()->contains($feed)) {
            $this->collFeeds->remove($this->collFeeds->search($feed));
            if (null === $this->feedsScheduledForDeletion) {
                $this->feedsScheduledForDeletion = clone $this->collFeeds;
                $this->feedsScheduledForDeletion->clear();
            }
            $this->feedsScheduledForDeletion[]= clone $feed;
            $feed->setCategory(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Category is new, it will return
     * an empty collection; or if this Category has previously
     * been saved, it will retrieve related Feeds from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Category.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Feed[] List of Feed objects
     */
    public function getFeedsJoinFeedType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = FeedQuery::create(null, $criteria);
        $query->joinWith('FeedType', $join_behavior);

        return $this->getFeeds($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->name = null;
        $this->parent_category_id = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
            if ($this->collChildrenCategorys) {
                foreach ($this->collChildrenCategorys as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collFeeds) {
                foreach ($this->collFeeds as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aParentCategory instanceof Persistent) {
              $this->aParentCategory->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collChildrenCategorys instanceof PropelCollection) {
            $this->collChildrenCategorys->clearIterator();
        }
        $this->collChildrenCategorys = null;
        if ($this->collFeeds instanceof PropelCollection) {
            $this->collFeeds->clearIterator();
        }
        $this->collFeeds = null;
        $this->aParentCategory = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(CategoryPeer::DEFAULT_STRING_FORMAT);
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

}
