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

## Context

Plates components support adding global context to be using solely during the render of a component tree. This enables a terse syntax when using custom helpers for framework integration or when you need to access data that should be easily accessible for more than one component and passing data down the component hierarchy doesn't make sense.

```php
<?php

use League\Plates\{ComponentContext, Portal};

$context = new ComponentContext();
// static values persist across renders, intended to be stateless
$context->addStatic(\App\User::class, $currentUser);
$context->addStatic(\Framework\RouteStore::class, $routes);

// factories will be reset per render to ensure that state doesn't carry over across top level renders.
$context->add(Portal::class, fn() => new Portal(), 'modals');
$context->add(Portal::class, fn() => new Portal(), 'js');

// Render your component within the provided context.
$context->render(new \App\UI\Components\MyMainComponent());
```

Accessing the context is done via the `context` global which **only** works within the bounds of the context's render function. Before and after the ComponentContext render function is called, the global `context` helper **will throw an exception.** This is to help ensure that this global state is only used within rendering from components.

```php
<?php

use function League\Plates\{p, context};

function Login() {
    return p(function() {
      $user = context(\App\User::class);
    ?> <h1>Hello, <?=$user->name()?></h1><?php
    });
}
```

## Portals

Portals are a special feature that allow you to render content outside of the current tree. These are similar to React Portals and Plates sections.

```php

use League\Plates\{ComponentContext, Portal};
use function League\Plates\{p, context};

// Register the portal in component context
$context = new ComponentContext();
$context->add(Portal::class, fn() => new Portal(), 'modals'); // add the alt so we can register/retrieve distinct instances of portal
$context->add(Portal::class, fn() => new Portal(), 'css');
$context->render(MyComponent());

// some component deep in the hierarchy
function MyComponent() {
  return p(function() {
  ?> 
    <div>Normal Component Content</div>   
  <?php
  
  context(Portal::class, 'css')
    ->append('<link href="/my/styles.css" rel="stylesheet"/>')
    ->append(function() {
    ?>
      <style>
        h1 { color: red; }
      </style>
    <?php
    });
  });
}

function LayoutComponent($children) {
    return p(function() use ($children) {
        $children = (string) p($children); // IMPORTANT: render children first so that the portal can be filled.
    ?> 
        <html>
          <head>
            <?=context(Portal::class, 'css')?>
          </head>
          <body>
            <div class="container mx-auto"><?=$children?></div>
            <?=context(Portal::class, 'modals')?>
          </body>
        </html>
    <?php
    });
}
```

Its important that there is a Portal::wrap over the entire component so that anything rendered will have all of the rendered portal sections replaced with the final versions of the portal content.

This works by entering unique tags in the rendered content, and then replacing them after everything is rendered.

// TECH NOTE, use str_replace, not strtr for performance reasons
