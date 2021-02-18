<?php

namespace Drupal\break_hello\Controller;

use Drupal\Core\Controller\ControllerBase;

class BreakHelloController extends ControllerBase {

  public function namePet() {
    return array(
      '#markup' => '<p>' . $this->t('Hello, Adopta un perro') . '</p>',
    );
  }
}
