<?php



/**
 * This class defines the structure of the 'rss_category' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.rss-reader.map
 */
class CategoryTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'rss-reader.map.CategoryTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('rss_category');
        $this->setPhpName('Category');
        $this->setClassname('Category');
        $this->setPackage('rss-reader');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 255, null);
        $this->addForeignKey('parent_category_id', 'ParentCategoryId', 'INTEGER', 'rss_category', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('ParentCategory', 'Category', RelationMap::MANY_TO_ONE, array('parent_category_id' => 'id', ), null, null);
        $this->addRelation('ChildrenCategory', 'Category', RelationMap::ONE_TO_MANY, array('id' => 'parent_category_id', ), null, null, 'ChildrenCategorys');
        $this->addRelation('Feed', 'Feed', RelationMap::ONE_TO_MANY, array('id' => 'category_id', ), null, null, 'Feeds');
    } // buildRelations()

} // CategoryTableMap
