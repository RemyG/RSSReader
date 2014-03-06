<?php

echo '
      <div class="entry-container" id="entry-container-'.$entry->getId().'">
        <div class="entry-link-container'.($entry->getRead() == 1 ? ' read' : '').($entry->getFavourite() == 1 ? ' favourite' : '').'">
          <div class="toggle-read">
            <a href="#" class="mark-read" data-id="'.$entry->getId().'" title="Mark read">
              <i class="fa fa-square-o"> </i>
            </a>
            <a href="#" class="mark-unread" data-id="'.$entry->getId().'" title="Mark unread">
              <i class="fa fa-check-square"> </i>
            </a>
          </div>
          <div class="toggle-favourite">
            <a href="#" class="mark-favourite" data-id="'.$entry->getId().'" title="Mark favourite">
              <i class="fa fa-star-o"> </i>
            </a>
            <a href="#" class="mark-unfavourite" data-id="'.$entry->getId().'" title="Mark not favourite">
              <i class="fa fa-star"> </i>
            </a>
          </div>
          <div  id="load-entry-link-'.$entry->getId().'"
              class="load-entry-link"
              data-id="'.$entry->getId().'"
              data-href="'.$entry->getLink().'"
              data-feed-id="'.$entry->getFeed()->getId().'"
              data-viewtype="'.($entry->getFeed()->getViewFrame() == 0 ? 'rss' : 'www').'">
            <div class="title-wrapper">
              '.(isset($catDisplay) ? '<div class="feed-title">'.$entry->getFeed()->getTitle().'</div>' : '').'
              <div class="title">'.$entry->getTitle().'</div>
            </div>
            <div class="date">'.$entry->getUpdated('Y-m-d').'</div>
          </div>
        </div>
        <div class="load-entry-div" id="load-entry-div-'.$entry->getId().'">
          <div class="entry-content"></div>
        </div>
      </div>';
