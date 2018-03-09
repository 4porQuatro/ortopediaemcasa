<div class="multi-level-menu">
    {!! $html !!}
</div>

@push('css')
    <style>
        .multi-level-menu ul
        {
            list-style: none;
        }

        .multi-level-menu ul li {
            margin-bottom: 3px;
            cursor: pointer;
        }

        .multi-level-menu ul li a {
            display: block;
        }

        .multi-level-menu > ul{
            margin-bottom: 0;
            padding-left: 0;
        }

        .multi-level-menu > ul > li > a {
            padding: 15px 25px;
            text-transform: uppercase;
            color: #ffffff;
            background-color: #48a99e;
            border: 1px solid #48a99e;
            transition-property: color, background-color;
            transition-duration: .4s;
        }

        .multi-level-menu > ul > li > a:hover,
        .multi-level-menu > ul > li.active > a {
            color: #48a99e;
            background-color: #ffffff;
        }

        .multi-level-menu a.toggler:after {
            content: '\f2f9';
            line-height: inherit;
            font: normal normal normal 14px/1 'Material-Design-Iconic-Font';
            font-size: .8em;
            float: right;
        }

        .multi-level-menu > ul ul {
            padding-top: 10px;
            padding-bottom: 10px;
            padding-left: 25px;
            display: none;
        }

        .multi-level-menu > ul ul a {
            padding: 6px 25px 0 0;
        }

        .multi-level-menu a:hover,
        .multi-level-menu li.active > a {
            color: #48a99e;
        }
    </style>
@endpush

@push('scripts')
    <script>
        /**
         * Muli Level Menu
         */
        function multiLevelMenu() {
            $menus = $('.multi-level-menu li > ul');

            $menus.each(function () {
                var $menu = $(this),
                    $toggler = $menu.parent().children('a').first(),
                    $anchors = $toggler.children('a');

                // find active items
                var $active_items = $menu.find('.active');

                if ($active_items.length) {
                    $menu.show();

                    if (!$toggler.parent().hasClass('active')) {
                        $toggler.parent().addClass('active');
                    }
                }

                $toggler.addClass('toggler');

                $anchors.on('click', function (event) {
                    event.preventDefault();
                });

                $toggler.on('click', function () {
                    $menu.slideToggle();
                });
            });
        }

        $(document).ready(function () {
            multiLevelMenu();
        });
    </script>
@endpush