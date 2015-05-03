<?php

class EntryController extends Controller
{
	/**
	 * EntryDAO.
	 * @var iEntryDAO
	 */
	private $entryDAO;
	
	/**
	 * FeedDAO
	 * @var iFeedDAO
	 */
	private $feedDAO;
	
	/**
	 * CategoryDAO
	 * @var iCategoryDAO
	 */
	private $categoryDAO;

	public function __construct($entryDAO = null, $feedDAO = null, $categoryDAO = null)
	{
		$this->entryDAO = $entryDAO != null ? $entryDAO : new EntryDAO();
		$this->feedDAO = $feedDAO != null ? $feedDAO : new FeedDAO();
		$this->categoryDAO = $categoryDAO != null ? $categoryDAO : new CategoryDAO();
	}

	/* ROUTES */

	public function load($id)
	{
		echo $this->loadWithTemplate($id, 'entry_load_view');
	}

	public function loadFrame($id)
	{
		echo $this->loadWithTemplate($id, 'entry_load_frame_view');
	}

	public function loadWithTemplate($id, $templateName)
	{
		$data = $this->logicLoad($id);
		return json_encode(array(
		    'feedId' => $data['feedId'],
		    'html' => $this->viewLoadWithTemplate($data, $templateName),
		    'feedCount' => $data['feedCount'],
		    'categoryCount' => $data['categoryCount']
		));
	}

	public function count($id)
	{
		return json_encode($this->logicCount($id));
	}

	public function markRead($id)
	{
		return json_encode($this->logicUpdateRead($id, 1));
	}

	public function markUnread($id)
	{
		return json_encode($this->logicUpdateRead($id, 0));
	}

	public function markFavourite($id)
	{
		return json_encode($this->logicUpdateFavourite($id, 1));
	}

	public function markUnfavourite($id)
	{
		return json_encode($this->logicUpdateFavourite($id, 0));
	}

	public function loadFavourites()
	{
		$data = $this->logicLoadFavourite();
		return json_encode($this->viewLoadFavourites($data));
	}

	/* LOGIC */

	/**
	 * Load the specific entry.
	 * 
	 * @param int $id The entry id.
	 * 
	 * @return array An array {'feedId', 'entry', 'feedCount', 'categoryCount'}, or
	 * an array {'error'} if something goes wrong.
	 */
	function logicLoad($id)
	{
		$id = FilterUtils::sanitizeVar($id, FILTER_VALIDATE_INT);

		if ($id === NULL || $id === false) {
			return array('error' => 'Invalid entry id');
		}

		$entry = $this->entryDAO->findById($id);

		if ($entry->getRead() === 0) {
			$entry->setRead(1);
			$this->entryDAO->save($entry);
		}

		$c = CriteriaFactory::getUnreadOrFavouriteEntriesCriteria();

		return array(
		    'feedId' => $entry->getFeed()->getId(),
		    'entry' => $entry,
		    'feedCount' => $this->feedDAO->countEntries($entry->getFeed(), $c),
		    'categoryCount' => $this->categoryDAO->countEntries($entry->getFeed()->getCategory(), $c));
	}

	/**
	 * Return the count of unread entries for the entry feed and category.
	 * 
	 * @param int $id The entry id.
	 * 
	 * @return array An array {'feed', 'category'} for the number of unread entries
	 * of the feed and category, or an array {'error'} if something goes wrong.
	 */
	function logicCount($id)
	{
		$id = FilterUtils::sanitizeVar($id, FILTER_VALIDATE_INT);

		if ($id === NULL || $id === false) {
			return array('error' => 'Invalid entry id');
		}

		$entry = $this->entryDAO->findById($id);

		$c = CriteriaFactory::getUnreadOrFavouriteEntriesCriteria();

		return array(
		    'feed' => $this->feedDAO->countEntries($entry->getFeed(), $c),
		    'category' => $this->categoryDAO->countEntries($entry->getFeed()->getCategory(), $c));
	}

	/**
	 * Update the specified entry with the status READ 0 or 1.
	 * 
	 * @param int $id The entry id.
	 * @param int $read The value of the READ field: 0 for unread, 1 for read.
	 * 
	 * @return array An array {'feedId', 'feedCount', 'categoryCount'}, or
	 * an array {'error'} if something goes wrong.
	 */
	function logicUpdateRead($id, $read)
	{
		$id = FilterUtils::sanitizeVar($id, FILTER_VALIDATE_INT);

		if ($id === NULL || $id === false) {
			return array('error' => 'Invalid entry id');
		}

		$entry = $this->entryDAO->findById($id);

		$entry->setRead($read);

		$this->entryDAO->save($entry);

		$c = CriteriaFactory::getUnreadOrFavouriteEntriesCriteria();

		return array(
		    'feedId' => $entry->getFeed()->getId(),
		    'feedCount' => $this->feedDAO->countEntries($entry->getFeed(), $c),
		    'categoryCount' => $this->categoryDAO->countEntries($entry->getFeed()->getCategory(), $c));
	}

	/**
	 * Update the specified entry with the status FAVOURITE 0 or 1.
	 * 
	 * @param int $id The entry id.
	 * @param int $favourite The value of the FAVOURITE field: 0 for not favourite, 1 for favourite.
	 * 
	 * @return array An array {'feedId'}, or an array {'error'} if something goes wrong.
	 */
	function logicUpdateFavourite($id, $favourite)
	{
		$id = FilterUtils::sanitizeVar($id, FILTER_VALIDATE_INT);

		if ($id === NULL || $id === false) {
			return array('error' => 'Invalid entry id');
		}

		$entry = $this->entryDAO->findById($id);

		$entry->setFavourite($favourite);

		$this->entryDAO->save($entry);
		
		$c = CriteriaFactory::getUnreadOrFavouriteEntriesCriteria();

		return array(
		    'feedId' => $entry->getFeed()->getId(),
		    'feedCount' => $this->feedDAO->countEntries($entry->getFeed(), $c),
		    'categoryCount' => $this->categoryDAO->countEntries($entry->getFeed()->getCategory(), $c));
	}

	/**
	 * Return the list of favourite entries.
	 * 
	 * @return array An array {'entries'}.
	 */
	function logicLoadFavourite()
	{
		return array('entries' => $this->entryDAO->getFavourites());
	}

	/* VIEW */

	function viewLoadWithTemplate($data, $templateName)
	{
		$template = $this->loadView($templateName);
		$template->set('entry', $data['entry']);
		return $template->renderString();
	}

	function viewLoadFavourites($data)
	{
		$template = $this->loadView('entry_favourite_view');
		$template->set('entries', $data['entries']);
		return $template->renderString();
	}

}
