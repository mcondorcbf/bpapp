(function ($) {

   $.fn.pickList = function (options) {

      var opts = $.extend({}, $.fn.pickList.defaults, options);

      this.fill = function () {
         var option = '';

         $.each(opts.data, function (key, val) {
            option += '<option value=' + val.id + '>' + val.text + '</option>';
         });
         this.find('.pickData').append(option);
      };
      this.controll = function () {
         var pickThis = this;

         pickThis.find(".pAdd").on('click', function () {
            var p = pickThis.find(".pickData option:selected");
            p.clone().appendTo(pickThis.find(".pickListResult"));
            p.remove();
         });

         pickThis.find(".pAddAll").on('click', function () {
            var p = pickThis.find(".pickData option");
            p.clone().appendTo(pickThis.find(".pickListResult"));
            p.remove();
         });

         pickThis.find(".pRemove").on('click', function () {
            var p = pickThis.find(".pickListResult option:selected");
            p.clone().appendTo(pickThis.find(".pickData"));
            p.remove();
         });

         pickThis.find(".pRemoveAll").on('click', function () {
            var p = pickThis.find(".pickListResult option");
            p.clone().appendTo(pickThis.find(".pickData"));
            p.remove();
         });
      };

      this.getValues = function () {
         var objResult = [];
         this.find(".pickListResult option").each(function () {
            objResult.push({
               id: $(this).data('id'),
               text: this.text
            });
         });
         return objResult;
      };

      this.init = function () {
         var pickListHtml =
                 "<div class='row'>" +
                 "<div class='col-sm-5'>" +
                 "<a href='#' class='list-group-item active'><span class='glyphicon glyphicon-th-large'></span> Clientes</a>" +
          "<select class='form-control pickListSelect pickData' name='clientesSelect' id='clientesSelect' multiple></select>" +
                 "</div>" +
                 "<div class='col-sm-2 pickListButtons'>" +
                 "<a class='pAdd btn btn-primary btn-sm'>" + opts.add + "</a><br>" +
                 "<a class='pAddAll btn btn-primary btn-sm'>" + opts.addAll + "</a><br>" +
                 "<a class='pRemove btn btn-primary btn-sm'>" + opts.remove + "</a><br>" +
                 "<a class='pRemoveAll btn btn-primary btn-sm'>" + opts.removeAll + "</a>" +
                 "</div>" +
                 "<div class='col-sm-5'>" +
                 "<a href='#' class='list-group-item active'><span class='glyphicon glyphicon-th-large'></span> Asesores</a>" +
                 "<select class='form-control pickListSelect pickListResult' name='clientesSelect2' id='clientesSelect2' multiple></select>" +
                 "</div>" +
                 "</div>";

         this.append(pickListHtml);
         this.fill();
         this.controll();
      };

      this.init();
      return this;
   };

   $.fn.pickList.defaults = {
      add: 'Agregar >',
      addAll: 'Agregar Todos >>',
      remove: '< Remover',
      removeAll: '<< Remover Todos'
   };


}(jQuery));