<div data-role="page">

	<div data-role="header" data-position="fixed">
		<h1><?php echo $entry->getTitle();?></h1>
		<a data-direction="reverse" data-iconpos="notext" data-icon="arrow-l" href="<?php echo $backUrl; ?>" ></a>
	</div>

	<div data-role="content">
		<?php echo $entry->getContent();?>
	</div>

</div>