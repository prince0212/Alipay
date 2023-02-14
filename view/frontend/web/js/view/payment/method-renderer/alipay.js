define(
    [
        'jquery',
        'Magento_Checkout/js/view/payment/default',
        'Magento_Checkout/js/action/select-payment-method',
        'Magento_Checkout/js/checkout-data',
        'mage/url'
    ],
    function ($, 
        Component,
        selectPaymentMethodAction,
        checkoutData,
        url
    ) {
        'use strict';

        return Component.extend({
            
            defaults: {
                template: 'Deloitte_Alipay/payment/form'
            },
            
            isActive: function() {
                return window.checkoutConfig.payment.alipay.active;
            },

            getCode: function() {
                return 'alipay';
            },
            
            selectPaymentMethod: function() {
                selectPaymentMethodAction(this.getData());
                checkoutData.setSelectedPaymentMethod(this.item.method);
                return true;
            },
            
            placeOrder: function (data, event) {
                $("body").loader("show");
                var alipay_gateway = url.build('alipay');
                window.location.replace(alipay_gateway);
            }
            
        });
    }
);