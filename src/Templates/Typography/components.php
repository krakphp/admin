<?php

namespace Krak\Admin\Templates\Typography;

use function League\Plates\attrs;
use function League\Plates\p;

function PageTitle(string $title) {
    return p(function() use ($title) {
        ?> <h1 class="font-medium text-2xl text-gray-900 mb-4"><?=$title?></h1> <?php
    });
}

function TextLink($content, string $href, array $attrs = []) {
  return p(function() use ($content, $href, $attrs) {
      ?> <a <?=attrs(['href' => $href, 'class' => 'text-blue-400 hover:text-blue-500 underline'])?>><?=p($content)?></a> <?php
  });
}
