{!! Form::open(['action' => "\App\Packages\ContactForm\ContactFormController@request", 'class' => "form-container"]) !!}
    @if (count($errors->contact_form) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->contact_form->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(Session::has('success_msg'))
        <div class="alert alert-success">
            {{ Session::get('success_msg') }}
        </div>
    @endif

    <div class="result-displayer"></div>

    <div class="form-group row">
        <div class="col-xs-12 col-md-7">
            <label class="contact-label" for="">Nome*</label>
            <input class="contact-input" type="text" name="name">
        </div>
        <div class="col-xs-12 col-md-5">
            <label class="contact-label" for="">Contacto</label>
            <input class="contact-input" type="text" name="phone">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-xs-12 col-md-7">
            <label class="contact-label" for="">Assunto*</label>
            <input class="contact-input" type="text" name="subject">
        </div>
        <div class="col-xs-12 col-md-5">
            <label class="contact-label" for="">Email*</label>
            <input class="contact-input" type="text" name="email">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-xs-12">
            <label class="contact-label" for="">Mensagem*</label>
            <textarea class="contact-input" name="message"></textarea>
        </div>
    </div>
    <div class="form-bottom-container">
        <button class="form-button" type="submit" name="button">enviar</button>
        <p class="form-bottom-text text-center">*Campos Obrigatórios</p>
    </div>
{!! Form::close() !!}
