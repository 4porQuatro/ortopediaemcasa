<div  class="faqs__wrapper" data-toggle="collapse" data-target="#collapse{{$key}}"  aria-expanded="false" role="button">
    <div class="faqs__container" id="accordion-faqs">
        <p class="faqs__question">{{$question}}</p>
        <a class="faqs__toggle accordion-toggle" href="javascript:void(0)"></a>
    </div>
    <div class="faqs__answer editable collapse" id="collapse{{$key}}">
        {!! $answer !!}
    </div>
</div>
