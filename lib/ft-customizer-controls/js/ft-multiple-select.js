(function($, api) {
  api.controlConstructor["ft-multiple-select"] = api.Control.extend({
    ready: function() {
      var control = this;

      $("select", control.container).change(function() {
        var value = $(this).val();

        if (null === value) {
          control.setting.set("");
        } else {
          control.setting.set(value);
        }
      });

      $("button.ft-select-all", control.container).on("click", function(event) {
        event.preventDefault();
        $("select option", control.container)
          .prop("selected", true)
          .change();
      });

      $("button.ft-deselect-all", control.container).on("click", function(
        event
      ) {
        event.preventDefault();
        $("select option", control.container)
          .prop("selected", false)
          .change();
      });
    }
  });
})(jQuery, wp.customize);
