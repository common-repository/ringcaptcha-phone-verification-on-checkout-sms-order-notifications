import { useEffect, useState } from '@wordpress/element';
import Widget from './Widget';

console.log('RingCaptcha Block: block.js loaded');

export const Block = ({ checkoutExtensionData }) => {
    const { setExtensionData } = checkoutExtensionData;
    const [phoneVerification, setPhoneVerification] = useState('');
    const [phoneNumber, setPhoneNumber] = useState('')

    useEffect(() => {
        setExtensionData('ringcaptcha', 'phoneVerification', phoneVerification);
    }, [phoneVerification, setExtensionData]);

    useEffect(() => {
        setExtensionData('ringcaptcha', 'phoneNumber', phoneNumber)
    }, [phoneNumber, setExtensionData]);

    return (
        <div className="wp-block-ringcaptcha-phone-verification">
            <Widget attributes={{ phoneVerification }} setPhoneNumber={setPhoneNumber} setAttributes={setPhoneVerification} />
        </div>
    );
};