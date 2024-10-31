document.addEventListener("DOMContentLoaded", function(event){  
  var widgetDOM = document.getElementById('widget-point');
  if (widgetDOM) {

    var userLocale = 'en';
    if (navigator.language) {
      userLocale = navigator.language;
    } else if (navigator.languages && navigator.languages.length > 0) {
      userLocale = navigator.languages[0];
    }

    widgetDOM.innerHTML = '<div id="xyz" data-widget data-locale="' + userLocale + '" data-type="dual" data-mode="signup"></div>';

    var $ = jQuery.noConflict();
    $('#xyz').each(function() {
      
      var app_key =  rc_options.app_key;
      var settings = $(this).data();
      settings.app = app_key;
      settings.locale = userLocale;
      settings.events = {
        ready: function(event, formValues) {
          $('.btn.btn-submit.btn-block.btn-verify.register').html("Request PIN");
          $('h3').text("");
        },

        signup: function(event, formValues) {
          console.log("Phone Verified!");
          $('h3').text("");
          $("div.signup-status").text("Thanks! Your phone has been verified!");

          if (rc_options.js_implementation == true) {
            $('input[name=ringcaptcha_phone_number]').val(formValues.phone);
            document.getElementById("ringcaptcha_verified").value = "true";
          }

          document.getElementById("gdpr_consent").value = formValues.gdpr;

          // Update the WooCommerce Blocks checkout store
          wp.data.dispatch('wc/store').setBillingData({
            billing_phone: formValues.phone,
            gdpr_consent: formValues.gdpr
          });
        }
      };

      settings.form = [
        {
          id: 'phone',
          type: 'phone',
          placeholder: 'Phone',
          validations: {
            length: { min: 5, max: 15, message: 'Doesn\'t look like a valid phone number' }
          }
        },
        {
          id: 'gdpr',
          type: 'checkbox',
          checked: true,
          label: rc_options.gdpr_consent_message,
        }
      ];
      new RingCaptcha.Widget(this, settings.app, settings);
    });
  } else {
    console.error("Element with ID 'widget-point' not found.");
  }
});
