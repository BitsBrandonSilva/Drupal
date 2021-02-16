console.log('Hola Mundo JavaScript');

(function ($, Drupal){
  'use strict';
  Drupal.behaviors.myModuleBehaviors = {
    attach: function (context, settings) {
      $(context).find('body').once('forcontu_hello').each(function (){
        $(document).ready(function (){
          console.log('Hola mundo, desde jquery!!');
          $.ajax({
            url: '/ajax/consulta',
            type: 'POST',
            data: ({node_id:7}),
            dataType: 'JSON',
            success: function (data) {
              console.log(data);
            }
          });
        });
      })
    }
  }
})(jQuery, Drupal)
