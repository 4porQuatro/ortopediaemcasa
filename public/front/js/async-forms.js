function asyncForms()
{
    var $forms = $('[data-async="true"]');

    // ajax request
    var submit = function(event){
        // disable form submission
        event.preventDefault();

        var html = '';

        var $form = $(event.target),
            $result_displayer = $form.find('.result-displayer');

        $.ajax({
            url: $form.attr('action'),
            type: "post",
            data: $form.serialize(),
            dataType: 'json',
            success: function(data)
            {
                if(typeof data.success_msg != 'undefined')
                {
                    html += '<div class="alert alert-success">' + data.success_msg + '</div>';
                }

                $result_displayer.html(html);
            },
            error: function(data)
            {
                html += '<div class="alert alert-danger">';
                html += '<ul>';
                for(key in data.responseJSON)
                {
                    html += '<li>' + data.responseJSON[key] + '</li>';
                }
                html += '</ul>';
                html += '</div>';

                $result_displayer.html(html);
            }
        });
    }

    // iterate forms
    $forms.each(function(){
        var $form = $(this);

        $form.on('submit', submit);
    });
}