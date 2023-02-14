define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'alipay',
                component: 'Deloitte_Alipay/js/view/payment/method-renderer/alipay'
            }
        );
        return Component.extend({});
    }
);
