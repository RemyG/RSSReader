<div id="slider-new-feed" class="slider">
	<div class="slider-header">
		<a href="#" class="close cancel-new-feed">&times;</a>
		<h3 class="slider-title">Add a new feed</h3>
	</div>
	<div class="slider-body">
		<div class="errors"></div>
		<form method="post" class="feed-form custom form-inline">
			<fieldset>
				<div class="field-group">
					<label for="feed-url">Feed URL</label>
					<input type="text" length="255" name="feed-url" id="feed-url" />
				</div>
				<div class="field-group">
					<label for="feed-category">Category</label>
					<select name="feed-category" id="feed-category" class="medium">
						<?php
							if (isset($categoriesTree))
							{
								foreach ($categoriesTree as $category)
								{
									echo '<option value="'.$category->getId().'">'.$category->getName().'</option>';
								}
							}
						?>
					</select>
				</div>
			</fieldset>
		</form>
	</div>
	<div class="slider-footer">
		<a href="#" class="btn btn-primary confirm-new-feed">Add feed</a>
		<a href="#" class="btn cancel-new-feed">Cancel</a>
	</div>
</div>