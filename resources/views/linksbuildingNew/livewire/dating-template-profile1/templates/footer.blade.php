<footer id="mainFooter">
    <div class="container text-white">
        <div class="widgets">
            <div class="row">

                @if(isset($footer_content_four_part))
                    <div class="col-lg-3">
                        <div class="widget text-center">
                            @if ($footer_content_first_part)
                                {!!__($footer_content_first_part)!!}
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="widget text-center">
                            @if ($footer_content_second_part)
                                {!!__($footer_content_second_part)!!}
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="widget text-center">
                            @if ($footer_content_third_part)
                                {!!__($footer_content_third_part)!!}
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="widget text-center">
                            @if ($footer_content_four_part)
                                {!!__($footer_content_four_part)!!}
                            @endif
                        </div>
                    </div>
                    @else
                        @if (isset($footer_content_third_part))
                            <div class="col-lg-4">
                                <div class="widget text-center">
                                    @if ($footer_content_first_part)
                                        {!!__($footer_content_first_part)!!}
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="widget text-center">
                                    @if ($footer_content_second_part)
                                        {!!__($footer_content_second_part)!!}
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="widget text-center">
                                    @if ($footer_content_third_part)
                                        {!!__($footer_content_third_part)!!}
                                    @endif
                                </div>
                            </div>
                        @else
                            @if (isset($footer_content_second_part))
                            <div class="col-lg-6">
                                <div class="widget text-center">
                                    @if ($footer_content_first_part)
                                        {!!__($footer_content_first_part)!!}
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="widget text-center">
                                    @if ($footer_content_second_part)
                                        {!!__($footer_content_second_part)!!}
                                    @endif
                                </div>
                            </div>
                            @else
                                @if (isset($footer_content_first_part))
                                    <div class="col-lg-12">
                                        <div class="widget text-center">
                                            @if ($footer_content_first_part)
                                                {!!__($footer_content_first_part)!!}
                                            @endif
                                        </div>
                                    </div>  
                                @endif
                            @endif
                        @endif
                @endif
            </div>
        </div>
    </div>
    <div class="footer__bottom">
      <div class="container">
        <div class="text-center">
          <span>© {{date('Y')}} {{$domain}} </span> <a href="#">All rights reserved.</a>
        </div>
      </div>
    </div>
    <button id="scroll-up">
      <i class="fas fa-chevron-up"></i>
    </button>
</footer>
