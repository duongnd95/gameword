<footer>
    <div class="container">
        <p class="footer__social">
            <a target="_blank" rel="nofollow" href="{!! $settings['follow_facebook'] !!}"><i class="fab fa-facebook px-2"></i></a>
            <a target="_blank" rel="nofollow" href="{!! $settings['follow_twitter'] !!}"><i class="fab fa-twitter px-2"></i></a>
            <a target="_blank" rel="nofollow" href="{!! $settings['follow_instagram'] !!}"><i class="fab fa-instagram px-2"></i></a>
            <a target="_blank" rel="nofollow" href="{!! $settings['follow_youtube'] !!}"><i class="fab fa-youtube"></i></a>
        </p>
        <div class="footer__social__content">
            <p>Phone:<strong> {{ $settings['company_phone'] }}</strong><br>
                Email: <strong>{{ $settings['company_email'] }}</strong><br>
                Address: {{ $settings['company_address'] }}
            </p>
        </div>
        <div class="footer__copyright">Copyright © 2024. All rights reserved</div>
    </div>
    <a class="back__top backtop" title="Lên đầu trang" href="#page-top"><i class="fa fa-angle-up"></i></a>
</footer>