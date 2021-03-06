# Nimble

[![Build Status](https://travis-ci.org/lumenpress/nimble.svg?branch=master)](https://travis-ci.org/lumenpress/nimble) [![Latest Stable Version](https://poser.pugx.org/lumenpress/nimble/v/stable)](https://packagist.org/packages/lumenpress/nimble) [![Total Downloads](https://poser.pugx.org/lumenpress/nimble/downloads)](https://packagist.org/packages/lumenpress/nimble) [![License](https://poser.pugx.org/lumenpress/nimble/license)](https://packagist.org/packages/lumenpress/nimble)

- [Post/Page](#)
  - [Models](#)
  - [Buidlers](#)
    - [Types](#)
    - [Status](#)
    - [Slug](#)
    - [Url](#)
    - [Where & whereIn & orWhere & orWhereIn](#)
    - [Order By](#)
- [Menu](#)
    - [Location](#)
    - [Slug](#)
    - [Collection](#)
- [Term](#)
  - [Models](#)
  - [Buidlers](#)
    [Taxonomy](#)
    [Exists](#)
    [Where & whereIn & orWhere & orWhereIn](#)
- [Taxonomy/Category/Tag](#)
- [User](#)
- [Comment](#)

```php
$post = new Post;
$post->title = 'Hello World';
$post->content = 'This is a post.';

// meta
$post->meta->foo = 'bar';
$post->meta->arr = ['value1', 'value2'];

// taxonomy
$post->tax->category = 'category name';
$post->tax->post_tag = ['tag1', 'tag2'];

// acf
// text type
$post->acf->text = 'Text1';

// group type
$post->acf->hero = [
  'image' => '/path/to/image.png',
  'link' => 'http://'
];

// repeater type
$post->acf->slides = [
  [
    'image' => '/path/to/image.png',
    'description' => 'some text1',
    'link' => 'http://'
  ],
  [
    'image' => '/path/to/image.png',
    'description' => 'some text2',
    'link' => 'http://'
  ],
];

$post->save();
```

## Post/Page

### Models

- Inserts

```php
$post = new Post;
$post->title = 'title';
$post->content = 'content';
$post->save();
```

- Updates

```php
$post = Post::find(1);
$post->title = 'title';
$post->content = 'content';
$post->save();
```

### Buidlers

- Types

```php
// single type
Post::type('post');             
// equal
Post::where('post_type', 'post');

// multiple types
Post::type('page', 'post');
Post::type(['page', 'post']);
// equal
Post::whereIn('post_type', ['page', 'post']);
```

- Status

```php
// single status
Post::status('publish');
// equal
Post::where('post_status', 'publish');

// multiple status
Post::status('publish', 'draft');
Post::status(['publish', 'draft']);
// equal
Post::whereIn('post_status', ['publish', 'draft']);
```

- Slug

```php
Post::slug('post-name');
// equal
Post::where('post_name', 'post-name');
```

- Url

```php
Page::url('parent-name/post-name');
// equal
$parent = Page::slug('parent-name')->first();
Page::parent($parent->id)->slug('post-name')->first();
```

- Where & whereIn & orWhere & orWhereIn

```php
// query from post field
Page::where('field', 'value');

// query from post meta key
Page::where('meta.key', 'value');

// query from term taxonomy
Page::where('term.taxonomy', 'taxonomy');

// query from term name
Page::where('term.name', 'term name');

// query from term meta key
Page::where('term.meta.key', 'value');
```

- Order By

```php
// order by post field
Page::type('page')->orderBy('date', 'asc'); // asc & desc

// order by meta key value
Page::type('page')->orderBy('meta.key', 'desc');
```

## Menu

### Location

```php
Menu::location('main');
Menu::location('footer');
```

### Slug

```php
Menu::slug('main');
```

### Collection

```php
$menus = Menu::get();
$menus['main']; // location name
$menus[1]; // menu id
```

## Term

### Models

```php
$term = new Term;
$term->taxonomy = 'category';
$term->name = 'Category Name';
$term->save();
```

### Buidlers

Taxonomy

```php
Term::taxonomy('category');
```

Exists

```php
Term::exists($taxonomy, $name, $parent = 0);
```

Where & whereIn & orWhere & orWhereIn

```php
// query from term field
Term::where('field', 'value');

// query from term meta key
Term::where('meta.key', 'value');
```

## Taxonomy/Category/Tag

comming soon

## User

comming soon

## Comment

comming soon