<div data-role="page">

	<div data-role="header" data-position="fixed">
		<h1><?php echo $category->getName(); ?></h1>
                <a data-direction="reverse" data-iconpos="notext" data-icon="arrow-l" href="<?php echo $backUrl; ?>" ></a>
	</div>

	<div data-role="content">

		<ul data-role="listview">
			<?php

				$c = new Criteria();
				$c->add(EntryPeer::READ, 0);
				foreach ($category->getFeeds() as $feed) {
					$disabled = "";
					if ($feed->getValid() == 0 && $feed->countEntrys($c) == 0)
					{
						$disabled = " disabled";
					}
					echo '
						<li>
							<a href="/m/feed/load/'.$feed->getId().'">
								'.$feed->getTitle().'
								<span class="ui-li-count ui-btn-corner-all countBubl">'.$feed->countEntrys($c).'</span>
							</a>
						</li>';
				}
							
			?>
		</ul>

	</div>

	
	
</div>
