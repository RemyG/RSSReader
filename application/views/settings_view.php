<h1>Settings</h1>

<h2>Categories</h2>

<div class="container">

	<div class="column">

		<ul>
			<?php
				foreach ($categories as $category)
				{
					echo '<li>'.$category->getName().'</li>';
				}
			?>
		</ul>

	</div>

	<div class="column">

		<h3>New category</h3>

		<form method="post" class="settings-form">
			<label for="category-name">Category name</label>
			<input type="text" name="category-text" id="category-text" required />
			<input type="hidden" name="action" value="new-category" />
			<input type="submit" value="Create category" />
		</form>

	</div>

</div>

<h2>Update user</h2>

<form method="post" class="settings-form" novalidate>
	<label for="login">Login</label>
	<input type="email" name="login" id="login" required />
	<label for="password">Password</label>
	<input type="password" name="password" id="password" required />
	<input type="hidden" name="action" value="update-user" />
	<input type="submit" value="Update" />
</form>