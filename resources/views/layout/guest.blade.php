@include('partials.header')
<main role="main" class="site">
  <div class="grid-cols-12">
    <section class="span-4 start-5 mx-auto auth">
      @yield('content')
    </section>
  </div>
</main>
@include('partials.footer')