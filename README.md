laravel-hmvc
============

Tiny library for making cross-controller requests in Laravel.

***Why would you want that?*** you may ask. The truth is that often you don't. There are many cases in which HMVC is not the best solution, which is why this is a bundle and not a core Laravel feature. Many times [View Composers](http://laravel.com/docs/views#view-composers) are sufficient to abstract common tasks from individual controllers, but I believe it is important to keep a distinction between controllers and composers: Controllers should gather data from Models to send to Views, and Composers are the "makeup" that fill in any details that the Controller doesn't need to worry about.

One use case for HMVC is to allow access to portions of content from the client: Let's say you have a page in your web application that includes bits of content from several different controllers, maybe even various bundles. If the user makes an ajax-driven change to some of that content, instead of trying to replicate the changed content on the client side or reloading the entire page, why not fetch from the server just the portion of content that was affected? And instead of creating that bit of content twice, create it once and use HMVC to decide how it gets to the user. I'll explain this further below with some code to illustrate.


Installation
------------
Use the Laravel artisan CLI tool to download the bundle:

	php artisan bundle:install hmvc

Add an entry into `application/bundles.php`:

```php

	return array(

		// ... existing bundles
		'hmvc',

	);
	
```


Usage
-----

In this example, we're fetching a bit of html from another controller

```php

	$content = HMVC::get('comments@for_user', array($user_id));

```

Inside the `for_user()` method, we could do something like this:

```php

	$table = '<table>…</table>';
	
	if (HMVC::active() || Request::ajax())
	{
		return $table;
	}
	
	return View::make('template')->with('content', $table);
	
```

See how that could be useful? You could build that `$table` once, then display it on a standalone page (wrapped in your template), on another controller's page as a partial, or returned by itself in response to an ajax request.

