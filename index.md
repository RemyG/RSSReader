---
layout: page
title: RSS Reader
---

# {{ page.title }}

<div class="grid-container">
	<div class="grid-100 main-description">
		<p>RSSReader has been created to be an open-source, self-hosted alternative to traditional RSS aggregators and readers like <a href="https://www.google.com/reader/">Google Reader</a>, <a href="https://www.netvibes.com/">Netvibes</a> or <a href="http://blog.feedly.com/">Feedly</a>.</p>
		<p></p>
	</div>
</div>

<div class="grid-container light-box">
	<div class="grid-50 justified large-padding">
		<h2>Self-hosted</h2>
		<p>RSSReader is meant to run on your own server. You only need a *AMP stack (Apache, MySql, PHP). This means that, unlike with RSS aggregators like Google Reader, Feedly or Netvibes, you <a href="http://userdatamanifesto.org/">own your data</a>. Your personal information won't ever be given or sold.</p>
		<p><a href="{{ site.baseurl }}/documentation.html">See the installation process</a></p>
	</div>
	<div class="grid-50 justified large-padding">
		<h2>Open-source</h2>
		<p>RSSReader is released under an <a href="http://opensource.org/licenses/MIT">MIT License</a>, which means that you can use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of this application. The only restriction attached to it is that you have to include the original license in your copy.</p>
		<p><a href="{{ site.baseurl }}/download.html">Download the sources</a></p>
	</div>
</div>
<div class="grid-container">
	<div class="grid-33">
		<h3>Latest posts</h3>
		<ul class="home-list">
			{% for post in site.posts limit: 5 %}
				<li>
					<a href="{{ site.baseurl }}{{ post.url }}">{{ post.title }}</a>
					<small><i class="icon-time"> </i>{{ post.date | date:"%Y-%m-%d" }}</small>
				</li>
			{% endfor %}
		</ul>
	</div>
	<div class="grid-33">
		<h3>Links</h3>
		<ul class="home-list">
			<li><a href="https://github.com/RemyG/RSSReader">RSSReader on Github</a></li>
			<li><a href="http://simplepie.org">SimplePie (RSS parser)</a></li>
			<li><a href="http://propelorm.org">PropelORM (PHP ORM)</a></li>
		</ul>
	</div>
	<div class="grid-33">
	</div>
</div>