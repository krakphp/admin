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


## Development Notes

Form Structure Usages

- Display
  - View (read) / Edit (write) / List (tables)
  - This is html based display types: Color Field, Json Field, Text, Text Area, Array fields
  - Fields can be used in any/all of the views, so those should be editable
  - There needs to be different representations display representations for tables, forms (edit), and read only viewing.
  - Likely would want support for nested structures, and maybe groups
- Validation
  - When submitting a form, there should be enough information on the field to allow for validation on the server, and maybe even
    frontend
- We only should need to describe one structure for a CRUD and that should be it, users just need to describe the structure
- Eventually, we'll want the ability to specify DTO's that contain attribute information which mimic the field type data
- Repository Services
  - Fetch data from Id
  - Search data (later)
  - save data
    - This will just save the raw array data, there can be a mapping layer beneath that is responsible for converting the array into a format to be saved.
- Data Access 
  - We'll need a simple data access interface that will wrap the data entries and provide a simple get interface for reading
  - The repositories will need to return entries of the data access objects