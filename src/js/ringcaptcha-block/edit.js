import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';

import Widget from './Widget';

export const Edit = ({ attributes, setAttributes }) => {
    const { phoneVerification } = attributes;
    const blockProps = useBlockProps();

    return (
        <div {...blockProps}>
            <InspectorControls>
                <PanelBody title={__('Phone Verification Settings', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications')}>
                    <TextControl
                        label={__('Verification Code', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications')}
                        value={phoneVerification}
                        onChange={(value) => setAttributes({ phoneVerification: value })}
                    />
                </PanelBody>
            </InspectorControls>
            <Widget attributes={attributes} setAttributes={setAttributes} />
        </div>
    );
};

export const Save = ({ attributes }) => {
    const { phoneVerification } = attributes;
    return (
        <div {...useBlockProps.save()}>
            <p>{phoneVerification}</p>
        </div>
    );
};