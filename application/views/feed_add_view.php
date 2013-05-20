<h1>Add a new feed</h1>

<form method="post" class="feed-form custom">
	<label for="feed-url">Feed URL</label>
	<input type="text" length="255" name="feed-url" id="feed-url" />
	<label for="feed-category">Category</label>
	<select name="feed-category" id="feed-category" class="medium">
		<?php
			foreach ($categories as $category)
			{
				echo '<option value="'.$category->getId().'">'.$category->getName().'</option>';
			}
		?>
	</select>
	<button type="submit" class="btn">Add feed</button>
</form>

<?php
	if (isset($errors) && count($errors) > 0)
	{
		foreach ($errors as $error)
		{
			echo '<p>'.$error.'</p>';
		}
	}
?>