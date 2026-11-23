<!doctype html>
<html @php(language_attributes())>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="text-scale" content="scale">
    @php(do_action('get_header'))
    @php(wp_head())

    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  <body @php(body_class())>
    @php(wp_body_open())

    <div id="app">
      <a class="sr-only focus:not-sr-only" href="#main">
        {{ __('Skip to content', 'sage') }}
      </a>

      <x-navbar
        :site-name="$siteName"
        :logo="$logoUrl"
        :home-url="$homeUrl"
        :live="$liveActive()"
        :live-url="$liveUrl"
        :search-url="$searchUrl"
        :footer-text="$navFooter"
      />

      <main id="main" class="main">
        @yield('content')
      </main>

      @hasSection('sidebar')
        <aside class="sidebar">
          @yield('sidebar')
        </aside>
      @endif

      <x-footer
        :site-name="$siteName"
        :tagline="$siteTagline"
        :copyright="$copyright"
        :follow="$followLinks()"
        :social="$socialLinks()"
        :columns="$footerColumns()"
      />
    </div>

    @php(do_action('get_footer'))
    @php(wp_footer())
  </body>
</html>
