@extends('/front/layouts/app')

@section('content')
    <div class="section first">
        <div class="container">
            <div class="row">

                <!-- Begin: Product Slide -->
                <div class="col-xs-12 col-md-6">
                    @include('front.components.product-slide', [
                        
                    ])
                </div>
                <!-- End: Product Slide -->

                <!-- Begin: Product Description -->
                <div class="col-xs-12 col-md-6">
                    @include('front.components.product-description',[
                    
                    ])
                </div>
                <!-- End: Product Description -->

            </div>
            <form class="product__form">
                <div class="row">
                    
                    <!-- Begin: Product Options -->
                    <div class="col-xs-12 col-md-6">
                        @include('front.components.product-options',[
                            
                        ])
                    </div>
                    <!-- End: Product Options -->

                    <!-- Begin: Product Purchase -->
                    <div class="col-xs-12 col-md-6">
                        @include('front.components.product-purchase', [
                        
                        ])
                    </div>
                    <!-- End: Product Purchase -->

                </div>
            </form>

            <!-- Begin: Product Bottom Nav -->
            @include('front.components.product-bottom-nav', [

            ])
            <!-- End: Product Bottom Nav -->
            
            <!-- Begin: Product Advise Slideshow -->
            <div class="slideshow-advise">
            
               
                        @include('front.components.product-card--small', [
                 
                        ])
          

                   
                        @include('front.components.product-card--small', [
                 
                        ])
         

             
                        @include('front.components.product-card--small', [
                 
                        ])

                        @include('front.components.product-card--small', [
                 
                        ])
         
                
            
            </div>
            <!-- End: Product Advise Slideshow -->

            <div class="section">
            <!-- Begin: Newsletter Form -->
                @include('front.components.newsletter', [

                ])
            <!-- End: Newsletter Form -->
            </div>
        </div>
    </div>
    
@endsection