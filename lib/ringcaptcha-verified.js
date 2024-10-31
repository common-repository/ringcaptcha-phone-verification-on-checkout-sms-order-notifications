document.addEventListener("DOMContentLoaded", function(event){
  // Appends widget div
  var widgetDOM = document.getElementById('widget-point');

  if (!widgetDOM) {
    console.error("Element with ID 'widget-point' not found!");
    return;
  }

  var userLocale = 'en';
  if (navigator.language) {
    userLocale = navigator.language;
  }

  widgetDOM.innerHTML = '<div id="xyz" data-widget data-locale="' + userLocale + '" data-type="dual" data-mode="signup"></div>';

    // This variable comes from the Wordpress Admin Settings
    var app_key = rc_options.app_key;
    var widget = new RingCaptcha.Widget('#xyz', {
      app: app_key,
      events: {
        verified: function (event, formValues) {
          console.log("Phone Verified!");
          document.getElementById("ringcaptcha_verified").value = "true";

          // Update the WooCommerce Blocks checkout store
          wp.data.dispatch('wc/store').setBillingData({
            billing_phone: formValues.phone
          });
        }
      }
    }).setup();
});