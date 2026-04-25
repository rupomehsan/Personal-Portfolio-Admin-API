# make:module Command — Field Type Reference

## Command Syntax

```
php artisan make:module {ModulePath} [{field:type,...}] --vue
```

**Examples:**
```bash
# Simple module
php artisan make:module Blog [title:string-150,content:longtext,is_published:boolean] --vue

# Nested module (creates BlogManagement/Blog directory)
php artisan make:module BlogManagement/Blog [title:string,category_id:bigint] --vue
```

---

## All Supported Field Types

### ── TEXT INPUTS ────────────────────────────────────────────────────────

| Type          | DB Column    | Frontend Input       | Example                        |
|---------------|--------------|----------------------|--------------------------------|
| `string`      | varchar(100) | text                 | `name:string`                  |
| `string-N`    | varchar(N)   | text                 | `title:string-150`             |
| `email`       | varchar(100) | email                | `email:email`                  |
| `url`         | varchar(100) | url                  | `website:url`                  |
| `tel` / `phone` | varchar(100) | tel                | `phone:tel`                    |
| `password`    | varchar(100) | password             | `password:password`            |
| `uuid`        | uuid         | text (readonly)      | `uuid:uuid`                    |

### ── TEXTAREA ───────────────────────────────────────────────────────────

| Type       | DB Column  | Frontend Input          | Example                    |
|------------|------------|-------------------------|----------------------------|
| `text`     | text       | textarea (4 rows)       | `description:text`         |
| `longtext` | longtext   | textarea (4 rows)       | `content:longtext`         |
| `json`     | json       | textarea (6 rows, JSON) | `meta:json`                |

### ── RICH TEXT EDITOR ───────────────────────────────────────────────────

| Type        | DB Column | Frontend Input | Example              |
|-------------|-----------|----------------|----------------------|
| `editor`    | longtext  | rich editor    | `body:editor`        |
| `richtext`  | longtext  | rich editor    | `content:richtext`   |
| `wysiwyg`   | longtext  | rich editor    | `article:wysiwyg`    |

### ── NUMERIC ────────────────────────────────────────────────────────────

| Type        | DB Column   | Frontend Input        | Example                   |
|-------------|-------------|-----------------------|---------------------------|
| `integer`   | integer     | number (step 1)       | `quantity:integer`        |
| `bigint`    | bigInteger  | number (step 1)       | `views:bigint`            |
| `float`     | float       | number (step 0.01)    | `rating:float`            |
| `decimal`   | decimal     | number (step 0.01)    | `price:decimal`           |
| `double`    | double      | number (step 0.01)    | `amount:double`           |
| `year`      | year        | number (1900–2100)    | `founded:year`            |

### ── DATE / TIME ────────────────────────────────────────────────────────

| Type        | DB Column | Frontend Input  | Example                     |
|-------------|-----------|-----------------|------------------------------|
| `date`      | date      | date picker     | `birth_date:date`            |
| `datetime`  | datetime  | datetime-local  | `publish_date:datetime`      |
| `timestamp` | timestamp | datetime-local  | `expires_at:timestamp`       |
| `time`      | time      | time picker     | `start_time:time`            |
| `month`     | month     | month picker    | `billing_month:month`        |

### ── FILE / IMAGE UPLOADS ───────────────────────────────────────────────

| Type         | DB Column    | Frontend Input              | Example                       |
|--------------|--------------|-----------------------------|-------------------------------|
| `image`      | varchar(100) | file (single, image/*)      | `thumbnail_image:image`       |
| `image-N`    | varchar(N)   | file (single, image/*)      | `thumbnail_image:image-150`   |
| `images`     | text (JSON)  | file (multiple, image/*)    | `gallery:images`              |
| `file`       | varchar(100) | file (single, docs+images)  | `attachment:file`             |
| `binary`     | binary       | file (single, docs+images)  | `document:binary`             |

### ── SELECT / ENUM ──────────────────────────────────────────────────────

| Type                      | DB Column  | Frontend Input          | Example                              |
|---------------------------|------------|-------------------------|--------------------------------------|
| `boolean`                 | tinyInt    | select → Yes / No       | `is_active:boolean`                  |
| `tinyint`                 | tinyInt    | select → Yes / No       | `is_featured:tinyint`                |
| `boolean-opt1.opt2`       | enum       | select → custom options | `status:boolean-active.inactive`     |
| `tinyint-opt1.opt2`       | enum       | select → custom options | `flag:tinyint-yes.no`                |
| `enum-opt1.opt2.opt3`     | enum       | select → custom options | `role:enum-admin.editor.viewer`      |
| `select`                  | varchar    | select (empty list)     | `category:select`                    |
| `multiselect`             | text       | multi-select (empty)    | `tags:multiselect`                   |
| `select-opt1.opt2`        | varchar    | select → custom options | `size:select-small.medium.large`     |
| `multiselect-opt1.opt2`   | text       | multi-select w/ options | `colors:multiselect-red.blue.green`  |

### ── RELATION / FOREIGN KEY ─────────────────────────────────────────────

| Type       | DB Column  | Frontend Input                 | Example                      |
|------------|------------|--------------------------------|------------------------------|
| `bigint`   | bigInteger | number (fill data_list in JS)  | `category_id:bigint`         |
| `relation` | varchar    | select (empty data_list)       | `user_id:relation`           |

> **Auto-detected:** Any field ending in `_id` (e.g. `blog_category_id:bigint`)
> automatically gets a **select** input with an empty `data_list` array.
> Populate it in `form_fields.js` or via a store action.

### ── SPECIAL ─────────────────────────────────────────────────────────────

| Type    | DB Column    | Frontend Input      | Example              |
|---------|--------------|---------------------|----------------------|
| `color` | varchar(100) | color picker        | `bg_color:color`     |
| `range` | integer      | range slider        | `volume:range`       |
| `range-min.max.step` | integer | range slider  | `rating:range-0.10.1`|

---

## Name-Based Auto-Detection

The generator infers the correct input type from the **field name** even when
you use a generic DB type like `string` or `bigint`.

| Field name pattern               | Auto-detected as       |
|----------------------------------|------------------------|
| `*_id` (e.g. `category_id`)      | relation select        |
| `*_image`, `thumbnail`, `avatar` | single image upload    |
| `*_images`, `gallery`, `photos`  | multi-image upload     |
| `is_*`, `has_*`                  | boolean Yes/No select  |
| `email`, `*_email`               | email input            |
| `phone`, `mobile`, `*_phone`     | tel input              |
| `url`, `link`, `website`, `*_url`| url input              |
| `color`, `*_color`               | color picker           |
| `password`, `*_password`         | password input         |
| `price`, `amount`, `*_price`     | decimal number         |

---

## Label Prefix Rules

| Field category      | Generated label prefix |
|---------------------|------------------------|
| Select / relation   | **Select** X           |
| File / image        | **Upload** X           |
| Everything else     | **Enter** X            |
| `*_id` relation     | strips `_id` from name |

*Example:* `blog_category_id:bigint` → label: **"Select Blog Category"**

---

## Complete Real-World Example

```bash
php artisan make:module BlogManagement/Blog \
  [blog_category_id:bigint,
   title:string-150,
   description:text,
   content:editor,
   reading_time:integer,
   tags:text,
   publish_date:datetime,
   writer:bigint,
   thumbnail_image:image-150,
   gallery:images,
   blog_type:enum-news.tutorial.opinion,
   url:url,
   show_top:boolean-yes.no,
   contributors:json,
   video_link:string-50,
   bg_color:color,
   rating:range-0.10.1,
   is_featured:boolean,
   is_published:boolean] --vue
```

| Field              | Type                  | DB             | Frontend                   |
|--------------------|-----------------------|----------------|----------------------------|
| blog_category_id   | bigint (auto: _id)    | bigInteger     | Select Blog Category       |
| title              | string-150            | varchar(150)   | text                       |
| description        | text                  | text           | textarea                   |
| content            | editor                | longtext       | rich editor                |
| reading_time       | integer               | integer        | number                     |
| tags               | text                  | text           | textarea                   |
| publish_date       | datetime              | datetime       | datetime-local             |
| writer             | bigint                | bigInteger     | number                     |
| thumbnail_image    | image-150             | varchar(150)   | Upload (single image/*)    |
| gallery            | images                | text           | Upload (multi image/*)     |
| blog_type          | enum-news.tutorial... | enum           | select 3 options           |
| url                | url                   | varchar(100)   | url input                  |
| show_top           | boolean-yes.no        | enum           | select Yes/No              |
| contributors       | json                  | json           | textarea (JSON)            |
| video_link         | string-50             | varchar(50)    | text                       |
| bg_color           | color                 | varchar(100)   | color picker               |
| rating             | range-0.10.1          | integer        | range slider (0–10, step 1)|
| is_featured        | boolean               | tinyInt        | select Yes/No              |
| is_published       | boolean               | tinyInt        | select Yes/No              |
