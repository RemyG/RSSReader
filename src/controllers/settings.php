<?php

class SettingsController extends Controller
{

    function index()
    {
        $template = $this->loadView('settings_view');
        $template->set('pageTitle', PROJECT_NAME);
        $template->set('pageDescription', 'Welcome to PFP - Main page');
        $categories = CategoryQuery::create()->orderByCatOrder()->findByParentCategoryId(1);
        $template->set('categories', $categories);
        $template->render();
    }

    /**
     * Build and print the OPML representation of all the categories and feeds.
     */
    function exportOpml()
    {
        $categories = CategoryQuery::create()->orderByCatOrder()->findByParentCategoryId(1);

        $xmlDoc = new DOMDocument();

        $simpleOpml = $xmlDoc->appendChild($xmlDoc->createElement('opml'));
        $simpleOpml->appendChild($xmlDoc->createAttribute('version'))
            ->appendChild($xmlDoc->createTextNode('2.0'));

        $simpleHead = $simpleOpml->appendChild($xmlDoc->createElement('head'));
        $simpleHead->appendChild($xmlDoc->createElement('dateCreated'))
            ->appendChild($xmlDoc->createTextNode(date('c')));
        $simpleHead->appendChild($xmlDoc->createElement('docs'))
            ->appendChild($xmlDoc->createTextNode('http://dev.opml.org/spec2.html'));

        $simpleBody = $simpleOpml->appendChild($xmlDoc->createElement('body'));

        foreach ($categories as $category) {
            $simpleCat = $simpleBody->appendChild($xmlDoc->createElement('outline'));
            $simpleCat->appendChild($xmlDoc->createAttribute('text'))
                ->appendChild($xmlDoc->createTextNode($category->getName()));

            foreach ($category->getFeeds() as $feed) {

                $simpleFeed = $simpleCat->appendChild($xmlDoc->createElement('outline'));
                $simpleFeed->appendChild($xmlDoc->createAttribute('text'))
                    ->appendChild($xmlDoc->createTextNode($feed->getTitle()));
                $simpleFeed->appendChild($xmlDoc->createAttribute('xmlUrl'))
                    ->appendChild($xmlDoc->createTextNode($feed->getLink()));
                $simpleFeed->appendChild($xmlDoc->createAttribute('htmlUrl'))
                    ->appendChild($xmlDoc->createTextNode($feed->getBaseLink()));
                $simpleFeed->appendChild($xmlDoc->createAttribute('type'))
                    ->appendChild($xmlDoc->createTextNode('rss'));
            }
        }

        $xmlDoc->formatOutput = true;

        Header('Content-type: text/xml');
        print($xmlDoc->saveXML());
    }
}

?>
