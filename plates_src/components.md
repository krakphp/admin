# Components 

## Components Extend Component 

The `League\Plates\Component` abstract class expects users to implement the __invoke method and simply echo any content they want to render.

The component's `__toString` is responsible for managing the output buffer and returning the string representation of that.

## Components Are Lazy

Components only render when they are actually echo'd out by another component or explicitly cast to a string.

## Class Components

```php
<?php

use League\Plates\Component;

final class Button extends Component
{
    private $title;
    
    public function __construct(string $title) {
        $this->title = $title;    
    }

    public function __invoke(): void {
        ?> <button><?=$this->title?></button> <?php
    }
}
```

## Function Components

```php
<?php

use function League\Plates\p;

function Button(string $title) {
    return p(function() use ($title) {
        ?> <button><?=$title?></button> <?php
    });
};
```

## Higher Order Components

```php
<?php

use function League\Plates\p;

function Container($component) {
    return p(function() use ($component) {
        ?> <div class="container mx-auto"><?=p($component)?></div> <?php
    });
};

// can be used like

?> 
  <?=Container('contents')?>
  <?=Container(Button('title'))?>
  <?=Container(function() {
    ?> <div>custom content</div> <?php
  })?>

<?php

```

## The `p` function

`League\Plates\p` is responsible for hoisting any values into a Component instance.

This will primarily be used for building function components and higher order components.

This function is safe to call on a component instance itself so it will never wrap a value that was already a component.
