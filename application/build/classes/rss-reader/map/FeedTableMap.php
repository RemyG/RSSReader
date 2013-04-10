<?php



/**
 * This class defines the structure of the 'rss_feed' table.
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
class FeedTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'rss-reader.map.FeedTableMap';

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
        $this->setName('rss_feed');
        $this->setPhpName('Feed');
        $this->setClassname('Feed');
        $this->setPackage('rss-reader');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('link', 'Link', 'VARCHAR', true, 255, null);
        $this->addColumn('title', 'Title', 'VARCHAR', true, 255, null);
        $this->addColumn('description', 'Description', 'LONGVARCHAR', false, 255, null);
        $this->addColumn('updated', 'Updated', 'TIMESTAMP', false, null, null);
        $this->addColumn('type_id', 'TypeId', 'INTEGER', true, null, null);
        $this->addForeignKey('category_id', 'CategoryId', 'INTEGER', 'rss_category', 'id', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Category', 'Category', RelationMap::MANY_TO_ONE, array('category_id' => 'id', ), null, null);
        $this->addRelation('Entry', 'Entry', RelationMap::ONE_TO_MANY, array('id' => 'feed_id', ), 'CASCADE', null, 'Entrys');
    } // buildRelations()

} // FeedTableMap
