<div id="feed-content">

	<?php 
	if (!isset($categoriesTree) || sizeof($categoriesTree) == 0)
	{
		echo "You haven't imported any feed yet. To do so, <a href='".BASE_URL."feed/add'><i class='icon-plus-sign'> </i> Import a new feed</a>
		or <a href='".BASE_URL."feed/importopml'><i class='icon-download'> </i> Import an OPML file</a>";
	}
	?>	

</div>

<!--<div id="modal-edit" class="modal hide fade">-->
<div id="modal-edit" class="modal fade">
	<div class="modal-header">
		<a href="#" class="close cancel-edit-feed">&times;</a>
		<h3>Edit feed</h3>
	</div>
	<div class="modal-body">
		<form id="form-edit">
			<fieldset>
				<input type="hidden" name="feed-id">
				<label>Feed title</label>
				<input type="text" name="feed-title">
				<label>Feed link</label>
				<input type="url" name="feed-link">
				<label>Feed base link</label>
				<input type="url" name="feed-base-link">
			</fieldset>
		</form>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-primary confirm-edit-feed">Save</a>
		<a href="#" class="btn cancel-edit-feed">Cancel</a>
	</div>
</div>

