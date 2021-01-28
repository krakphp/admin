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

### Todo List

There are two scopes of work, first build specific functionality, then start abstracting that functionality

**Specific**

- Build Test Suite for demo app Admin functionality
  - Should include some html utilities as well, maybe even page object model helpers
- Add some form of form validation to use
- Add link fields to link to other admin pages for related entities
- Build admin for Target Size scales
- Build admin for size scale mappings to show example of selecting a reference entity field.
- only allow deleting draft size scales
- published size scales can only be archived
- archiving can happen if we duplicate the product.

**Abstract**

- Abstract test suite utilities to make it easy to test CRUD actions, searching/filtering, etc etc
- Abstract admin functionality
- Proper Theming Support

#### Abstraction

  - once done, build helpers as well that can be used
    to easily test such functionality
- Build entity/admin for Store entity that takes a custom address field and status field of open/offline to test select boxes
- Start abstracting admin functionality for entities

### Form Structure Usages

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
