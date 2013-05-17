---
layout: page
title: Blog Posts
---

<div id="blog">
	<div class="row">
		<div class="large-12 columns">
			<h2>{{ page.title }}</h2>
		</div>
	</div>
	<div class="row">
		<div class="large-12 columns">
			<ul class="posts">
				{% for post in site.posts %}
					<li><span class="date">{{ post.date | date_to_string }}</span> &raquo; <a href="{{ site.baseurl }}{{ post.url }}">{{ post.title }}</a></li>
				{% endfor %}
			</ul>
		</div>
	</div>
</div>