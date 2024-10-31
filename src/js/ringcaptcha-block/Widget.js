import { useEffect } from '@wordpress/element';

const Widget = ({ attributes, setAttributes, setPhoneNumber }) => {
    const { phoneVerification } = attributes;

    useEffect(() => {
        const acceptLanguage = navigator.language || 'en';
        const userLocale = acceptLanguage.split(',')[0].substr(0, 2);
        const gdprImplementation = rc_options.gdpr_implementation || false;
        const jsImplementation = rc_options.js_implementation || false;
        const appKey = rc_options.app_key || '';
        const gdprConsentMessage = rc_options.gdpr_consent_message || "I would like to receive discount updates and promotions in accordance with GDPR standards.";

        if (gdprImplementation) {
            document.getElementById('widget-point').innerHTML = '<div id="xyz" data-widget data-locale="' + userLocale + '" data-type="dual" data-mode="signup"></div>';
            document.getElementById('gdpr_consent').value = "false";

            if (jsImplementation) {
                document.getElementById('ringcaptcha_verified').value = "false";
            }

            new RingCaptcha.Widget('#xyz', {
                app: appKey,
                locale: userLocale,
                events: {
                    verified: function () {
                        document.getElementById("ringcaptcha_verified").value = "true";
                        setAttributes("verified");
                    }
                }
            }).setup();
        } else if (jsImplementation) {
            document.getElementById('widget-point').innerHTML = '<div id="xyz" data-widget data-locale="' + userLocale + '" data-type="dual" data-mode="signup"></div>';
            document.getElementById('ringcaptcha_verified').value = "false";

            new RingCaptcha.Widget('#xyz', {
                app: appKey,
                locale: userLocale,
                events: {
                    verified: function () {
                        document.getElementById("ringcaptcha_verified").value = "true";
                        setAttributes("verified");
                    }
                }
            }).setup();
        } else {
            document.getElementById('widget-point').innerHTML = '<div data-widget data-app="' + appKey + '" data-locale="' + userLocale + '" data-type="dual"></div>';

            new RingCaptcha.Widget('[data-widget]', {
                app: appKey,
                locale: userLocale,
                events: {
                    verified: function () {
                        document.getElementById("ringcaptcha_verified").value = "true";
                        const phoneNumberField = document.getElementsByName('ringcaptcha_phone_number')
                        setAttributes("verified");
                        setPhoneNumber(phoneNumberField[0].value)
                    }
                }
            }).setup();
        }

        // Check if any elements are being hidden unintentionally
        document.querySelectorAll('.panel.woocommerce_options_panel').forEach(function(panel) {
            console.log('Panel ID:', panel.id, 'Display:', panel.style.display);
        });

    }, []);

    return (
        <div>
            <div id="widget-point"></div>
            <input id="gdpr_consent" type="hidden" name="gdpr_consent" value="false" />
            <input id="ringcaptcha_verified" type="hidden" name="ringcaptcha_verified" value="false" />
        </div>
    );
};

export default Widget;