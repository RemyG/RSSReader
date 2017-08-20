<?php

class EntryController extends Controller
{

    private $dateFormat = 'Y-m-d';

    /**
     * EntryDAO.
     *
     * @var iEntryDAO
     */
    private $entryDAO;

    /**
     * FeedDAO
     *
     * @var iFeedDAO
     */
    private $feedDAO;

    /**
     * CategoryDAO
     *
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
    public function proxy($url, $hash)
    {
        $realUrl = urldecode($url);
        $realHash = crypt($realUrl, PROXY_SALT);
        if ($hash == $realHash) {
            $ch = curl_init($realUrl);

            // identify request headers
            $request_headers = array();
            foreach ($_SERVER as $key => $value) {
                if (strpos($key, 'HTTP_') === 0 || strpos($key, 'CONTENT_') === 0) {
                    $headername = str_replace('_', ' ', str_replace('HTTP_', '', $key));
                    $headername = str_replace(' ', '-', ucwords(strtolower($headername)));
                    if (! in_array($headername, array(
                        'Host',
                        'X-Proxy-Url'
                    ))) {
                        $request_headers[] = "$headername: $value";
                    }
                }
            }

            curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers); // (re-)send headers
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return response
            curl_setopt($ch, CURLOPT_HEADER, true); // enabled response headers

            $response = curl_exec($ch);
            curl_close($ch);
            // split response to header and content
            list ($response_headers, $response_content) = preg_split('/(\r\n){2}/', $response, 2);
            // (re-)send the headers
            $response_headers = preg_split('/(\r\n){1}/', $response_headers);
            foreach ($response_headers as $key => $response_header) {
                // Rewrite the `Location` header, so clients will also use the proxy for redirects.
                if (preg_match('/^Location:/', $response_header)) {
                    list ($header, $value) = preg_split('/: /', $response_header, 2);
                    $response_header = 'Location: ' . $_SERVER['REQUEST_URI'] . '?csurl=' . $value;
                }
                if (! preg_match('/^(Transfer-Encoding):/', $response_header)) {
                    header($response_header, false);
                }
            }
            // finally, output the content
            print($response_content);
        }
    }

    public function load($id)
    {
        $data = $this->logicLoad($id);
        echo json_encode(array(
            'feedId' => $data['feedId'],
            'html' => $this->viewLoadWithTemplate($data, 'entry_load_view'),
            'feedCount' => $data['feedCount'],
            'categoryCount' => $data['categoryCount']
        ));
    }

    public function loadFrame($id)
    {
        $data = $this->logicLoad($id);
        echo json_encode(array(
            'feedId' => $data['feedId'],
            'html' => $this->viewLoadWithTemplate($data, 'entry_load_frame_view'),
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
    
    public function markToRead($id)
    {
        return json_encode($this->logicUpdateToRead($id, 1));
    }

    public function markUntoread($id)
    {
        return json_encode($this->logicUpdateToRead($id, 0));
    }

    public function loadFavourites()
    {
        $data = $this->logicLoadFavourite();
        $template = $this->viewLoadFavourites($data);
        return json_encode(array(
            'html' => $template,
            'count' => sizeof($data),
            'counts' => array()
        ));
    }

    /**
     * Return a json representation of the entries for a specific date.
     *
     * @param string $date
     *            the date in format 'Y-m-d'.
     * @return type
     */
    public function loadByDate($date = null)
    {
        if (sizeof($date) == 0) {
            $date = date_format(new DateTime(), $this->dateFormat);
        }
        $data = $this->logicLoadByDate($date);
        $template = $this->viewLoadByDate($data, $date);
        return json_encode(array(
            'html' => $template,
            'count' => sizeof($data),
            'counts' => array()
        ));
    }
	
	/**
     * Return a json representation of the entries for a specific date.
     *
     * @param string $date
     *            the date in format 'Y-m-d'.
     * @return type
     */
    public function markReadByDate($date = null)
    {
        if (sizeof($date) != 0) {
			$entries = EntryQuery::create()
				->filterByPublished(array("min" => $date." 00:00:00", "max" => $date." 23:59:59"))
				->find();
			foreach ($entries as $entry) {
				$entry->setRead(1);
				$entry->save();
			}
            $data = $this->logicLoadByDate($date);
			$template = $this->viewLoadByDate($data, $date);
        }        
        return json_encode(array(
            'html' => $template,
            'count' => sizeof($data),
            'counts' => array()
        ));
    }
	
	/**
     * Return a json representation of the entries for a specific date.
     *
     * @param string $date
     *            the date in format 'Y-m-d'.
     * @return type
     */
    public function markUnreadByDate($date = null)
    {
        if (sizeof($date) != 0) {
			$entries = EntryQuery::create()
				->filterByPublished(array("min" => $date." 00:00:00", "max" => $date." 23:59:59"))
				->find();
			foreach ($entries as $entry) {
				$entry->setRead(0);
				$entry->save();
			}
            $data = $this->logicLoadByDate($date);
			$template = $this->viewLoadByDate($data, $date);
        }        
        return json_encode(array(
            'html' => $template,
            'count' => sizeof($data),
            'counts' => array()
        ));
    }

    /* LOGIC */

    /**
     * Load the specific entry.
     *
     * @param int $id
     *            The entry id.
     *
     * @return array An array {'feedId', 'entry', 'feedCount', 'categoryCount'}, or
     *         an array {'error'} if something goes wrong.
     */
    function logicLoad($id)
    {
        $id = FilterUtils::sanitizeVar($id, FILTER_VALIDATE_INT);

        if ($id === NULL || $id === false) {
            return array(
                'error' => 'Invalid entry id'
            );
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
            'categoryCount' => $this->categoryDAO->countEntries($entry->getFeed()
                ->getCategory(), $c)
        );
    }

    /**
     * Return the count of unread entries for the entry feed and category.
     *
     * @param int $id
     *            The entry id.
     *
     * @return array An array {'feed', 'category'} for the number of unread entries
     *         of the feed and category, or an array {'error'} if something goes wrong.
     */
    function logicCount($id)
    {
        $id = FilterUtils::sanitizeVar($id, FILTER_VALIDATE_INT);

        if ($id === NULL || $id === false) {
            return array(
                'error' => 'Invalid entry id'
            );
        }

        $entry = $this->entryDAO->findById($id);

        $c = CriteriaFactory::getUnreadOrFavouriteEntriesCriteria();

        return array(
            'feed' => $this->feedDAO->countEntries($entry->getFeed(), $c),
            'category' => $this->categoryDAO->countEntries($entry->getFeed()
                ->getCategory(), $c)
        );
    }

    /**
     * Update the specified entry with the status READ 0 or 1.
     *
     * @param int $id
     *            The entry id.
     * @param int $read
     *            The value of the READ field: 0 for unread, 1 for read.
     *
     * @return array An array {'feedId', 'feedCount', 'categoryCount'}, or
     *         an array {'error'} if something goes wrong.
     */
    function logicUpdateRead($id, $read)
    {
        $id = FilterUtils::sanitizeVar($id, FILTER_VALIDATE_INT);

        if ($id === NULL || $id === false) {
            return array(
                'error' => 'Invalid entry id'
            );
        }

        $entry = $this->entryDAO->findById($id);

        $entry->setRead($read);

        $this->entryDAO->save($entry);

        $c = CriteriaFactory::getUnreadOrFavouriteEntriesCriteria();

        return array(
            'feedId' => $entry->getFeed()->getId(),
            'feedCount' => $this->feedDAO->countEntries($entry->getFeed(), $c),
            'categoryCount' => $this->categoryDAO->countEntries($entry->getFeed()
                ->getCategory(), $c)
        );
    }

    /**
     * Update the specified entry with the status FAVOURITE 0 or 1.
     *
     * @param int $id
     *            The entry id.
     * @param int $favourite
     *            The value of the FAVOURITE field: 0 for not favourite, 1 for favourite.
     *
     * @return array An array {'feedId'}, or an array {'error'} if something goes wrong.
     */
    function logicUpdateFavourite($id, $favourite)
    {
        $id = FilterUtils::sanitizeVar($id, FILTER_VALIDATE_INT);

        if ($id === NULL || $id === false) {
            return array(
                'error' => 'Invalid entry id'
            );
        }

        $entry = $this->entryDAO->findById($id);

        $entry->setFavourite($favourite);

        $this->entryDAO->save($entry);

        $c = CriteriaFactory::getUnreadOrFavouriteEntriesCriteria();

        return array(
            'feedId' => $entry->getFeed()->getId(),
            'feedCount' => $this->feedDAO->countEntries($entry->getFeed(), $c),
            'categoryCount' => $this->categoryDAO->countEntries($entry->getFeed()
                ->getCategory(), $c)
        );
    }
    
    /**
     * Update the specified entry with the status READ 0 or 1.
     *
     * @param int $id
     *            The entry id.
     * @param int $read
     *            The value of the READ field: 0 for unread, 1 for read.
     *
     * @return array An array {'feedId', 'feedCount', 'categoryCount'}, or
     *         an array {'error'} if something goes wrong.
     */
    function logicUpdateToRead($id, $read)
    {
        $id = FilterUtils::sanitizeVar($id, FILTER_VALIDATE_INT);

        if ($id === NULL || $id === false) {
            return array(
                'error' => 'Invalid entry id'
            );
        }

        $entry = $this->entryDAO->findById($id);

        $entry->setToRead($read);

        $this->entryDAO->save($entry);

        $c = CriteriaFactory::getUnreadOrFavouriteEntriesCriteria();

        return array(
            'feedId' => $entry->getFeed()->getId(),
            'feedCount' => $this->feedDAO->countEntries($entry->getFeed(), $c),
            'categoryCount' => $this->categoryDAO->countEntries($entry->getFeed()
                ->getCategory(), $c)
        );
    }

    /**
     * Return the list of favourite entries.
     *
     * @return array An array {'entries'}.
     */
    function logicLoadFavourite()
    {
        return $this->entryDAO->getFavourites();
    }

    /**
     * Return the list of entries for the specified date.
     *
     * @param String $searchDate
     *            the date in format Y-m-d.
     *
     * @return type the list of entries.
     */
    function logicLoadByDate($searchDate)
    {
        return $this->entryDAO->findByDate($searchDate);
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
        $template->set('entries', $data);
        return $template->renderString();
    }

    function viewLoadByDate($data, $searchDate)
    {
        $template = $this->loadView('entry_bydate_view');
        $template->set('entries', $data);
        $template->set('date', $searchDate);
        $template->set('prev_date', date_format(date_sub(new DateTime($searchDate), new DateInterval('P1D')), $this->dateFormat));
        $template->set('next_date', date_format(date_add(new DateTime($searchDate), new DateInterval('P1D')), $this->dateFormat));
        return $template->renderString();
    }
}
