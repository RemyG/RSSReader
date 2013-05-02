<div data-role="page">

	<div data-role="header">
		<h1>Categories</h1>
	</div>

	<div data-role="content">

		<ul data-role="listview">
			<?php
				if (isset($categoriesTree))
				{
					foreach ($categoriesTree as $category)
					{
						echo '<li><a href="/m/feed/category/'.$category->getId().'">'.$category->getName().'</a></li>';
					}
				}
			?>
		</ul>
		
	</div>
	
</div>