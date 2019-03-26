@extends('layouts/mainlayout')

{{-- Page title --}}
@section('title')
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('public/css/custom_css/layout_responsive.css') }}">
@stop


{{-- Page content --}}
@section('content')


    <section class="content-header">
                    <!--section starts-->
                    <h1>Fixed Header &amp; Menu</h1>
                    <ol class="breadcrumb">
                        <li>
                            <a href="#">Layouts</a>
                        </li>
                        <li>Fixed Header &amp; Menu</li>
                    </ol>
                </section>
                <section class="content">
                    <div class="outer">
                        <div class="inner bg-light lter">
                            <h2>Code</h2>
                            <pre><code class="language-markup">

                        &lt;nav class=&quot;navbar-fixed-top&quot;&gt;
                        ...
                        &lt;/nav&gt;

                        &lt;aside class=&quot;left-side sidebar-offcanvas fixed&quot;&gt;
                        ...
                        &lt;/aside&gt;
                        </code></pre>

                        </div>
                        <!-- /.inner --> 
                    </div>
                    <div class="col-lg-12">

                    <div class="clearfix"></div>
                </div>
                </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
@stop