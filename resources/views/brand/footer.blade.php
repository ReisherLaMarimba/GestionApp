@guest
    <div class="m-4 text-center text-muted">
        <p>Developed

            by Reisher Mella
        </p>
    </div>

    <p class="small text-center mb-1 px-5">
        {{ __('The application code is published under the MIT license.') }}
    </p>
@else

    <div class="text-center user-select-none my-4 d-none d-lg-block">
        <p class="small mb-0">
            {{ __('The application code is published under the MIT license.') }} 2016 - {{date('Y')}}<br>
            <a href="http://orchid.software" target="_blank" rel="noopener">
                {{ __('Version') }}: {{\Orchid\Platform\Dashboard::version()}}
                Reisher Mella
            </a>
        </p>
    </div>
@endguest
