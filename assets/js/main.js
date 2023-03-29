jQuery(function( $ ) {
  $('input[type=submit].mt-btn-submit').on('click', function(e) {
    e.preventDefault();
    var wpurl = WPURLS.siteurl;
    var _this = $(this);
    var target = _this.attr('data-target');
    var data = $('#form-' + target).serializeArray();
    var errorTarget = $('.error-' + target);
    var successTarget = $('.success-' + target);
    var url_redir = $( "input[name='mailtarget_form_redir-"+ target +"']" ).val();
    var formData = new FormData($('#form-' + target)[0]);
    formData.append('mailtarget_ajax_post', true)
    errorTarget.hide();
    successTarget.hide();
    _this.attr('disabled', 'disabled');
    $.ajax({
    type: 'POST',
    dataType: "JSON",
    data: formData,
    processData: false,
    contentType: false,
    success: function(response) {
      _this.removeAttr('disabled');
      if (response.code !== undefined) {
      switch (response.code) {
        case 400:
        errorTarget.text(response.msg);
        errorTarget.show();
        break;
        case 200:
        successTarget.text('Form submitted successfully.');
        successTarget.show();
        $('#form-' + target).hide();
        if ('' !== url_redir) {
          setTimeout(function() {
            document.location.href = url_redir
          }, 2000)
        }
        break;
      }
      }
    }
    });
  })
});