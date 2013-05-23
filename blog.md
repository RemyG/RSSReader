---
layout: page
title: Blog Posts
---

# {{ page.title }}

{% for post in site.posts %}

<article class="post post-list">
	<header>
		<div class="post-title">
			<h1>
				<a title="Permalink to {{ post.title }}" href="{{ site.baseurl }}{{post.url}}/">{{ post.title }}</a>
				{% if post.tagline %}<small> - {{post.tagline}}</small>{% endif %}
			</h1>
		</div>
		<div class="post-meta">
			<div class="post-date left">
				<i class="icon-time"> </i>
				{{ post.date | date:"%Y-%m-%d" }}
			</div>
			<div class="comments-heading right">
				<i class="icon-comments"> </i>
				<a href="{{ post.url }}/#disqus_thread">Comments</a>
			</div>
		</div>
	</header>
	<div class="post-wrapper">
		<div class="post-content">
			{{ post.content }}
		</div>
	</div>
	<div class="meta-info">
		{% unless post.tags == empty %}
		<div>
			<ul class="tag_box inline-list valign-middle">
				<li><i class="icon-tags valign-middle float-left"> </i></li>
				{% assign tags_list = post.tags %}
				{% include tags_list %}
			</ul>
		</div>
		{% endunless %}
		{% unless post.categories == empty %}
		<div>
			<ul class="tag_box inline-list valign-middle">
				<li><i class="icon-folder-open valign-middle float-left"> </i></li>
				{% assign categories_list = post.categories %}
				{% include categories_list %}
			</ul>
		</div>
		{% endunless %}
	</div>
</article>

{% endfor %}
