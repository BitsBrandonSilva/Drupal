<?php
/**
* @file
* Contains \Drupal\Capacitacion\forcontu_hello\Controller\ForcontuHelloController.
*/
namespace Drupal\forcontu_hello\Controller;
use Drupal\Core\Controller\ControllerBase;

/**
* Controlador para devolver el contenido de las páginas definidas
*/
class ForcontuHelloController extends ControllerBase {
    public function hello() {
        return array(
            '#markup' => '<p>' . $this->t('Hello, World! This is my
            first module in Drupal 9!') . '</p>',
        );
    }
}