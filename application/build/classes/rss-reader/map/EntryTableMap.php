<?php



/**
 * This class defines the structure of the 'rss_entry' table.
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
class EntryTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'rss-reader.map.EntryTableMap';

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
        $this->setName('rss_entry');
        $this->setPhpName('Entry');
        $this->setClassname('Entry');
        $this->setPackage('rss-reader');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('published', 'Published', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated', 'Updated', 'TIMESTAMP', false, null, null);
        $this->addColumn('link', 'Link', 'VARCHAR', true, 255, null);
        $this->addColumn('title', 'Title', 'VARCHAR', true, 255, null);
        $this->addColumn('description', 'Description', 'LONGVARCHAR', false, null, null);
        $this->addColumn('author', 'Author', 'VARCHAR', false, 255, null);
        $this->addColumn('read', 'Read', 'TINYINT', true, null, null);
        $this->addColumn('content', 'Content', 'LONGVARCHAR', false, null, null);
        $this->addForeignKey('feed_id', 'FeedId', 'INTEGER', 'rss_feed', 'id', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Feed', 'Feed', RelationMap::MANY_TO_ONE, array('feed_id' => 'id', ), 'CASCADE', null);
    } // buildRelations()

} // EntryTableMap
