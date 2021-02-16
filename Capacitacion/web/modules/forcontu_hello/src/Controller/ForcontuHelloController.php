<?php
/**
* @file
* Contains \Drupal\Capacitacion\forcontu_hello\Controller\ForcontuHelloController.
*/
namespace Drupal\forcontu_hello\Controller;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
* Controlador para devolver el contenido de las páginas definidas
*/
class ForcontuHelloController extends ControllerBase {
  protected $service;

  public function __construct()
  {
    $this->service = \Drupal::service('forcontu_hello.tools');
  }

  public function hello() {
        $build = [];

        //----Consulta
        $node = \Drupal::entityTypeManager()->getStorage('node')->load(1);
        $form = \Drupal::formBuilder()->getForm('Drupal\forcontu_hello_forms\Form\Simple');
        dpm('HolaMundo_desde el controlador');
        dpm($node->toArray());
        $build['#markup'] = '<p>' . $this->t('Hello, World! This is my first module in Drupal 9!!!!') . '</p>';
        $build[] = $form;
        $build['template'] = [
          '#theme' => 'forcontu_hello',
          '#my_node' => $node->toArray(),
          '#cache' => [ 'max-age' => 0 ],
          '#attached' => [
            'library' => [
              'forcontu_hello/forcontu_hello'
            ]
          ]
        ];

      /**
       * Coneccion a la base de datos
       */
        $connection = \Drupal::database();


      /***
       * Método Connection:query_range()
       * permite obtener un número limitado de
       * registros en la consulta.
       */
        $uid = 1;
        $result = $connection->queryRange('SELECT * FROM {watchdog} u
            WHERE u.uid = :uid', 0, 2, [':uid' => $uid]);
        foreach ($result as $record){
          dpm($record);
        }

      /***
       * sentencias dinámicas por partes
       * Sentencia SELECT dinamica
       * se conoce en Drupal como DBTNG
       */
      $query = $connection->select('node_counter', 'n')
        ->fields('n')
        ->condition('n.totalcount', 1, '>');

      $query->orderBy('n.totalcount', 'DESC')->orderBy('n.timestamp', 'DESC');

      /**
       * función de depuración dpq()
       * que devuelve la sentencia SQL a partir de un objeto $query de tipo DBTNG.
       */
      dpq($query);

      /***
       * Fields()
       * Permite añadir varios campos a la consulta en un único paso,
       * indicando el alias de la tabla y un array con los nombres de los campos.
       */
      $query->fields('n', ['nid', 'totalcount', 'daycount']);
      $query->fields('n'); // Añade todos los campos (SELECT *)
      dpq($query);

      $result = $query->execute();

      foreach ($result as $record){
        dpm($record);
      }

      /**
       * Fields()
       * añade un conjunto de pares campo->valor, que serán
       * insertados en un registro de la tabla.
       *
      $connection->insert('forcontu_hello_test_one')
        ->fields([
          'guitar' => 'Jackson',
          'type' => 'v',
          'cuerdas' => 6
        ])
        ->execute();
      */

      /**
       * Values()
       * Funcion para agrgar varios registros a una tabla
       */
      $insert = $connection->insert('forcontu_hello_test_one')
        ->fields(['guitar', 'type', 'cuerdas']);

        //---Primer registro a insertar
      $insert->values([
        'guitar' => 'Epiphone',
        'type' => 'SG-Special VE',
        'cuerdas' => 6
      ]);

      //---Segundo registro
      $insert->values([
        'guitar' => 'Ibanez',
        'type' => 'JEMJR-WH',
        'cuerdas' => 7
      ]);

      $insert->values([
        'guitar' => 'B.C. Rich',
        'type' => 'IRONBIRD EXTREME WITH FLOYD ROSE',
        'cuerdas' => 6
      ])

      ->execute($insert);

      /**
       * Sentencia estatica select
       */
      $result = $connection->query('SELECT * FROM {forcontu_hello_test_one}');
      foreach ($result as $record){
        dpm($record);
      }
      dpm('Consulta a la tabla forcontu_hello_test_one');

      /**
       * Sentencia dinamica Update()
       *
       * La clase Update crea una sentencia de tipo UPDATE,
       * que realiza cambios en uno o más registros de una tabla de la base de datos.
       */
      $connection->update('forcontu_hello_test_one')
        ->fields([
          'guitar' => 'Fender',
          'type' => 'V',
          'cuerdas' => 7
        ])
        ->condition('guitar', 'Ibanez', '=')
        ->execute();

      /**
       * Sentencia dinamica Delete()
       * La clase Delete ejecuta una sentencia DELETE
       * para eliminar los registros de la tabla que cumplan las condiciones especificadas.
       */
      $connection->delete('forcontu_hello_test_one')
        ->condition('guitar', 'Epiphone', '=')
        ->execute();


      /**
       * Sentencia truncate()
       * es una forma más optimizada de vaciar completamente una tabla.
       */
      //$connection->truncate('forcontu_hello_test_one')->execute();

      return $build;
    }

  /**
   * @param Request $request
   * @return JsonResponse
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * Llamado ajax en Drupal
   */
    public function my_first_ajax_call(Request $request) {
      if($request->isXmlHttpRequest()) {
        $node_id = $request->request->get('node_id');
        $node = \Drupal::entityTypeManager()->getStorage('node')->load($node_id)->toArray();
        return new JsonResponse(['node' => $node]);
      } else {
        throw new \Exception('INTRUDER!!!');
      }
    }
}
