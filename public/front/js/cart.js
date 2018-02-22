function initCheckout(iso) {
    var setItemsForms = function ()
    {
        var $items_displayer = $('#cart-items-displayer'),
            $forms = $items_displayer.find('form');

        $forms.each(function ()
        {
            var $form = $(this);

            $form.on('submit', function (event)
            {
                event.preventDefault();

                $.ajax({
                    url: $form.attr('action'),
                    type: $form.attr('method'),
                    data: $form.serialize(),
                    success: function (data) {
                        console.log(data);
                        initCheckout(iso);
                    }
                });
            });

            /* quantity forms */
            var $qty_input = $form.find('[name="quantity"]');

            if ($qty_input.length) {
                $qty_input.on('change input', function () {
                    $form.submit();
                });
            }
        })
    }

    var cartItems = function ()
    {
        var $items_displayer = $('#cart-items-displayer');

        $.ajax({
            url: iso + '/checkout/items',
            type: 'get',
            success: function (data) {
                $items_displayer.html(data);

                // set items forms
                setItemsForms();
            }
        });
    }

    var shippingMethods = function () {
        var $shipping_displayer = $('#checkout-shipping-displayer');

        $.ajax({
            url: iso + '/checkout/shipping-methods',
            type: 'get',
            success: function (data) {
                $shipping_displayer.html(data);

                setShippingOptions();
            }
        });

    }

    var setShippingOptions = function () {
        var $shipping_displayer = $('#checkout-shipping-displayer'),
            $options = $shipping_displayer.find('[type="radio"]');

        $options.on('change', function () {
            $.ajax({
                url: iso + '/cart/add-shipping-method',
                type: 'post',
                data: {shipping_method_id: $options.filter(':checked').val()},
                success: function (data) {
                    cartSummary();
                }
            });
        });
    }

    var cartSummary = function () {
        var $summary_displayer = $('#cart-summary-displayer');
        $.ajax({
            url: iso + '/checkout/summary',
            type: 'get',
            success: function (data) {
                $summary_displayer.html(data);
            }
        });
    }

    var setVoucher = function () {
        var $input = $('#voucher'),
            $add_btn = $('#add-voucher-btn'),
            $remove_btn = $('#remove-voucher-btn'),
            $displayer = $('#voucher-results-displayer');

        $add_btn.on('click', function ()
        {
            // reset css classes
            $input.removeClass('input-success input-error');

            $.ajax({
                url: iso + '/voucher/add',
                type: 'post',
                data: {
                    voucher: $input.val()
                },
                success: function (data) {
                    $displayer.html(data.success);

                    $input.addClass('input-success');
                },
                error: function (data) {
                    $displayer.html(data.responseJSON.voucher);

                    $input.addClass('input-error');
                }
            })
            .always(function () {
                cartSummary();
            });
        });

        $remove_btn.on('click', function () {
            $.ajax({
                url: iso + '/voucher/remove',
                type: 'post',
                success: function (data) {
                    $displayer.html(data.success);
                }
            })
            .always(function () {
                cartSummary();
                $input.val('');
            });
        })
    }

    var setPoints = function()
    {
        var $input = $('#points-input'),
            $add_btn = $('#add-points-btn'),
            $remove_btn = $('#remove-points-btn'),
            $displayer = $('#points-results-displayer');

        $add_btn.on('click', function ()
        {
            // reset css classes
            $input.removeClass('input-success input-error');

            $.ajax({
                url: iso + '/points/add',
                type: 'post',
                data: {
                    points: $input.val()
                },
                success: function (data) {
                    $displayer.html(data.success);

                    $input.addClass('input-success');
                },
                error: function (data) {
                    $displayer.html(data.responseJSON.points);

                    $input.addClass('input-error');
                }
            })
            .always(function () {
                cartSummary();
            });
        });

        $remove_btn.on('click', function () {
            $.ajax({
                url: iso + '/points/remove',
                type: 'post',
                success: function (data) {
                    $displayer.html(data.success);
                }
            })
            .always(function () {
                cartSummary();
                $input.val('');
            });
        })
    }

    cartItems();
    shippingMethods();
    cartSummary();
    setVoucher();
    setPoints();
}
