<div data-role="page">

	<div data-role="header" data-position="fixed">
		<h1>Categories</h1>
		<a href="/">Desktop</a>
		<a href="/m/user/logout" data-icon="delete" class="ui-btn-right">Logout</a>
	</div>

	<div data-role="content">

		<ul data-role="listview">
			<?php
				if (isset($categoriesTree))
				{
					foreach ($categoriesTree as $category)
					{
						echo '
							<li>
								<a href="/m/feed/category/'.$category->getId().'">'.$category->getName().'
									<span class="ui-li-count ui-btn-corner-all countBubl">'.$category->countEntrys().'</span>
								</a>
							</li>';
					}
				}
			?>
		</ul>
		
	</div>
	
</div>
