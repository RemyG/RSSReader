<div data-role="page">

	<div data-role="header">
		<h1><?php echo $category->getName(); ?></h1>
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
								<span class="feed-title">'.$feed->getTitle().'</span>
								<span class="feed-count">'.$feed->countEntrys($c).'</span>
							</a>
						</li>';
				}
							
			?>
		</ul>

	</div>

	
	
</div>