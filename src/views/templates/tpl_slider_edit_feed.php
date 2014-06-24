<div id="slider-edit-feed" class="slider">
		<div class="slider-header">
			<a href="#" class="close cancel-edit-feed">&times;</a>
			<h3 class="title">Edit feed</h3>
		</div>
		<div class="slider-body">
			<form id="form-edit" class="form-inline">
				<fieldset>
					<input type="hidden" name="feed-id" value="<?php echo $feed->getId(); ?>">
					<div class="field-group">
						<label>Feed title</label>
						<input type="text" name="feed-title" value="<?php echo $feed->getTitle(); ?>">
					</div>
					<div class="field-group">
						<label>Feed link</label>
						<input type="url" name="feed-link" value="<?php echo $feed->getLink(); ?>">
					</div>
					<div class="field-group">
						<label>Feed base link</label>
						<input type="url" name="feed-base-link" value="<?php echo $feed->getBaseLink(); ?>">
					</div>
					<div class="field-group">
						<label for="feed-category">Category</label>
						<select name="feed-category" id="feed-category">
							<?php
								foreach ($categories as $category)
								{
									if ($category->getId() == $feed->getCategory()->getId())
									{
										echo '<option value="'.$category->getId().'" selected="selected">'.$category->getName().'</option>';
									}
									else
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
			<a href="#" class="btn btn-primary confirm-edit-feed">Save</a>
			<a href="#" class="btn cancel-edit-feed">Cancel</a>
		</div>
	</div>