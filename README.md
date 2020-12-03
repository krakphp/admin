# Admin

The admin library is an easy to use, highly configurable admin generator built on top of PlatesPHP and TailwindCSS. The primary integration point is with Symfony/Doctrine, but the design is such that it's not coupled directly to those technologies.

It's designed from the ground up to work well with anemic CRUD entities along with rich domain entities. 

## Usage

### Defining Your Theme

If you'd like to customize the theme in any way, you'll need to configure the Theme class.

```php
Theme::overrideDefaults([
  'button' => MyButtonElement::class,
  'link' => function(string $text, string $href, array $attrs = []) {
    ?> <a href="<?=$href?>"  <?=attrs($attrs)?>><?=$text?></a> <?php
  },
]);
```

## Roadmap / Tasks

### Build static create/read/update/delete admin for two entities

### Extract the Form Validation Piece

### Extract the Templating
