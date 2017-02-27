# Shomeya Utility module for Drupal

This module provides several useful features, such as an environment indicator, a style guide, some render elements and template functions/helpers.

## Build metadata

This module provide a helper class to access information about the build, such as latest Git commit, date, time, as well as who and where the build was packaged. This information is available in the status report at admin/reports/status. It also includes links to Github for relevant commits as long as the Github URL is set in settings.php:

```php
$config['shomeya_utility.settings']['github_url'] = 'https://github.com/shomeya/projectname';
```

## Environment indicator

An environment indicator is available that provides text and an icon indicating which environment you are viewing:

![environment indicator for production with green checkmark](/images/environment-indicator.png?raw=true)

This functionality is based on the enviroment specified in the settings.json file and is mapped in src/Environment.php.

## Style Guide

This module provides a sample style guide at styleguide/web with additional pages for form elements and Drupal elements at styleguide/web/drupal/form and styleguide/web/drupal/elements.

## Elements

A time element is defined in src/Element/Time.php. This element also supports optionally rendering as a dynamic 'time ago' with Javascript if the option is enabled. 

```php
$build = [
  '#type' => 'time',
  '#timestamp' => '1456527560', // Unix timestamp to use for the date, will be used to populate a ISO 8601 in the 'datetime' attribute of the tag
  '#value' => 'Friday, Feb. 26th', // The value to be displayed
  '#options' => [
    'timeago' => TRUE, // Whether or not the element should be rendered as timeago using javascript
  ],
  '#attributes' => [ // additional attributes for the element
  	'class' => 'my-time-element', 
  ],
];
```

## Template functions/helpers

There are several helper function that are exposed to Twig templates:

#### URL from URI

This helper turns a Drupal URI (such as `internal:/about-us` , `entity:node/1234`, or `base:robots.txt`) into a fully qualified URL:

```twig
<a href="{{ url_from_uri(node.field_link.uri) }}">My Link</a>
```

It supports options that will be based to [Url::fromUri()](https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Url.php/function/Url%3A%3AfromUri/8) such as `query`, `absolute`, `https`, and more:

```twig
<a href="{{ url_from_uri(node.field_link.uri, {absolute: true}) }}">My Link</a>
```

#### Time

Supports creation of a time element from a timestamp:

```twig
{# Without value provided a value will be formatted from the timestamp #}
{{ time(item.timestamp) }}
```

It supports an optional value to be displayed if you wish to customize the date display:

```twig
{{ time(item.timestamp, item.date) }}
```

Finally it supports all options that can be passed to the time element, and additional options for generating a value if one is not provided:

```twig
{# Type and format correspond to options passed to DateFormatter::format() #}
{{ time(item.timestamp, null, {type: custom, format: 'F j Y'}) }}
```

The last example will output something like:

```html
<time datetime="2016-02-26T14:59:20-08:00">February 26 2016</time>
```

#### Time ago

Support time element from timestamp with timeago set to true:

```twig
{{ time_ago(item.timestamp) }}
```

The output will be:

```html
<time datetime="2016-02-26T14:59:20-08:00" data-drupal-time-ago>Fri, 02/26/2016 - 14:59</time>
```

This also automatically includes [jquery.timeago.js](http://timeago.yarp.com) which will format the value as a time ago, such as  'less than a minute ago.'

This support the same options for value and options as the time helper.

#### Time From String

This function supports generating a time element from a date string parsed with [strtotime()](http://php.net/strtotime):

```twig
{{ time_from_string('Fri, 02/26/2016 - 14:59') }}
```

Also allows for value, options.

#### Time ago from string

Supports generating a time ago tag from a string:

```twig
{{ time_ago_from_string('Fri, 02/26/2016 - 14:59') }}
```