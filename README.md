# Blog System

A multi-author blog built with Laravel 12. Authenticated users write posts,
file them under a category, tag them, and publish when ready.

## Data model

| Model | Notes |
|---|---|
| `Post` | Belongs to a user and a category, has many tags |
| `Category` | One category per post |
| `Tag` | Many-to-many with posts through a `post_tag` pivot |
| `User` | Author; Laravel Breeze handles authentication |

Posts carry a title, slug, excerpt, body, featured image, and a
`status` enum of `draft` or `published` with a `published_at` timestamp.
`Post::scopePublished()` keeps drafts out of public listings, so an unpublished
post is invisible to readers without the controller having to filter by hand.

## Routes

Reading is public — the post index and individual posts are open. Creating,
editing and deleting posts requires authentication, as do the category and tag
resource controllers.

## Running it

Requires PHP 8.2+, Composer and Node.js.

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate
# set your database connection in .env

php artisan migrate
npm run dev
php artisan serve
```

## Stack

Laravel 12 · PHP 8.2 · Blade · Tailwind CSS · Laravel Breeze
