<?php

class CategoryController extends Controller {

	private $categoryDAO;
	private $feedDAO;
	private $entryDAO;

	private $feedController;

	public function __construct($categoryDAO = null, $feedDAO = null, $entryDAO = null)
	{
		$this->categoryDAO = $categoryDAO != null ? $categoryDAO : new CategoryDAO();
		$this->feedDAO = $feedDAO != null ? $feedDAO : new FeedDAO();
		$this->entryDAO = $entryDAO != null ? $entryDAO : new EntryDAO();

		$this->feedController = new FeedController();
	}

	/*** ROUTES ***/

	function order($catId, $order)
	{
		$this->logicOrder($catId, $order);
	}

	function load($id, $all = null)
	{
		$result = $this->logicLoad($id, $all);
		$template = $this->viewLoad($result);
		return json_encode(array('html' => $template->renderString(), 'count' => $result['count'], 'counts' => $result['counts']));
	}

	function create()
	{
		return json_encode($this->logicCreate());
	}

	function update($id)
	{
		$this->logicUpdate($id);
		return $this->load($id);
	}

	function count($id)
	{
		return $this->logicCount($id);
	}

	function markRead($id)
	{
		$this->logicMarkRead($id);
		return $this->load($id);
	}

	function markNotRead($id)
	{
		$this->logicMarkNotRead($id);
		return $this->load($id);
	}

	/*** LOGIC ***/

	/**
	 * Re-order the categories by setting a specific position to a categorie, and
	 * moving the other categories if required.
	 *
	 * @param int $catId
	 * 		The category id.
	 * @param int $order
	 * 		The new position for this category (0-based).
	 */
	function logicOrder($catId, $order)
	{
		$categories = $this->categoryDAO->findByParentId(1);
		$i = 0;
		foreach ($categories as $tmpCat) {
			if ($i < $order) {
				if ($catId == $tmpCat->getId()) {
					$tmpCat->setCatOrder($order);
				}
				else {
					$tmpCat->setCatOrder($i);
					$i++;
				}
			}
			else if ($i >= $order) {
				if ($catId == $tmpCat->getId()) {
					$tmpCat->setCatOrder($order);
				}
				else {
					$tmpCat->setCatOrder($i + 1);
					$i++;
				}
			}
			$this->categoryDAO->save($tmpCat);
		}
	}

	/**
	 * Open a category and all of its entries, and display it in the view <code>category_load_view</code>.
	 *
	 * @param int $id
	 * 		The category id.
	 * @param int $all [default = null]
	 * 		1 if we want to display all the entries, null or 0 if we want to display only the un-read entries.
	 *
	 * @return
	 * 		An array containing the category ['category'], the list of entries ['entries'], the number of unread
	 *		entries for this category ['count'] and an array of { feedId => nb unread entries} ['counts'].
	 */
	function logicLoad($id, $all = null)
	{
		$category = $this->categoryDAO->findById($id);

		if ($all == null || $all == 0) {
			$entries = $this->categoryDAO->getUnreadEntries($id);
		}
		else {
			$entries = $this->categoryDAO->getAllEntries($id);
		}

		$c = new Criteria();
		$c->add(EntryPeer::READ, 0);
		$counts = array();
		foreach ($category->getFeeds() as $feed) {
			$counts[$feed->getId()] = $feed->countEntrys($c);
		}
		return array('category' => $category, 'entries' => $entries, 'count' => $category->countEntrys($c), 'counts' => $counts);
	}

	/**
	 * Create a new category.
	 *
	 * @return
	 *		An array containing either the 'id', 'name' and 'order' of the new category,
	 * 		or the 'error' that happened.
	 */
	function logicCreate()
	{
		$catName = filter_input(INPUT_POST, 'categoryName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		if ($catName != null && $catName != false) {
			try {
				$category = new Category();
				$category->setName($catName);
				$category->setParentCategoryId(1);
				$catOrder = $this->categoryDAO->findNextCatOrder();
				$category->setCatOrder($catOrder + 1);
				$this->categoryDAO->save($category);
				return array('id' => $category->getId(), 'name' => $category->getName(), 'order' => $category->getCatOrder());
			}
			catch (Exception $e) {
				return array('error' => $e->getMessage());
			}
		}
		else {
			return array('error' => "Category name not set.");
		}
	}

	/**
	 * Update every feed of this category, and return the result of $this->load.
	 *
	 * @param int $id
	 * 		The category id.
	 *
	 * @return The result of $this->load.
	 */
	function logicUpdate($id)
	{
		$category = $this->categoryDAO->findById($id);
		foreach ($category->getFeeds() as $feed) {
			$this->feedController->update($feed->getId());
		}
	}

	/**
	 * Return the number of un-read entries for this category.
	 *
	 * @param int $id
	 * 		The category id.
	 *
	 * @return The number of un-read entries.
	 */
	function logicCount($id)
	{
		$category = $this->categoryDAO->findById($id);
		return $category->countEntrys();
	}

	/**
	 * Mark all entries from this category as read
	 *
	 * @param int $id
	 * 		The category id.
	 *
	 * @return The result of $this->load($id).
	 */
	function logicMarkRead($id)
	{
		$category = $this->categoryDAO->findById($id);
		foreach ($category->getFeeds() as $feed) {
			foreach ($feed->getEntrys() as $entry) {
				$entry->setRead(1);
				$this->entryDAO->save($entry);
			}
		}
	}

	/**
	 * Mark all entries from this category as not read
	 *
	 * @param int $id
	 * 		The category id.
	 *
	 * @return The result of $this->load($id).
	 */
	function logicMarkNotRead($id)
	{
		$category = $this->categoryDAO->findById($id);
		foreach ($category->getFeeds() as $feed) {
			foreach ($feed->getEntrys() as $entry) {
				$entry->setRead(0);
				$this->entryDAO->save($entry);
			}
		}
	}

	/*** VIEWS ***/

	function viewLoad($data)
	{
		$template = $this->loadView('category_load_view');
		$template->set('category', $data['category']);
		$template->set('entries', $data['entries']);
		return $template;
	}
}