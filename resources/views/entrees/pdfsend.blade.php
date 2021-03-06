<style>
    .row{font-size:15px;}

    html {
        font-family: 'Open Sans', sans-serif;
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%; }

    body {
        margin: 0; }

    article,
    aside,
    details,
    figcaption,
    figure,
    footer,
    header,
    hgroup,
    main,
    menu,
    nav,
    section,
    summary {
        display: block; }

    audio,
    canvas,
    progress,
    video {
        display: inline-block;
        vertical-align: baseline; }

    audio:not([controls]) {
        display: none;
        height: 0; }

    [hidden],
    template {
        display: none; }

    a {
        background-color: transparent; }

    a:active,
    a:hover {
        outline: 0; }

    abbr[title] {
        border-bottom: 1px dotted; }

    b,
    strong {
        font-weight: bold; }

    dfn {
        font-style: italic; }

    h1 {
        font-size: 2em;
        margin: 0.67em 0; }

    mark {
        background: #ff0;
        color: #000; }

    small {
        font-size: 80%; }

    sub,
    sup {
        font-size: 75%;
        line-height: 0;
        position: relative;
        vertical-align: baseline; }

    sup {
        top: -0.5em; }

    sub {
        bottom: -0.25em; }

    img {
        border: 0; }

    svg:not(:root) {
        overflow: hidden; }

    figure {
        margin: 1em 40px; }

    hr {
        box-sizing: content-box;
        height: 0; }

    pre {
        overflow: auto; }

    code,
    kbd,
    pre,
    samp {
        font-family: monospace, monospace;
        font-size: 1em; }

    button,
    input,
    optgroup,
    select,
    textarea {
        color: inherit;
        font: inherit;
        margin: 0; }

    button {
        overflow: visible; }

    button,
    select {
        text-transform: none; }

    button,
    html input[type="button"],
    input[type="reset"],
    input[type="submit"] {
        -webkit-appearance: button;
        cursor: pointer; }

    button[disabled],
    html input[disabled] {
        cursor: default; }

    button::-moz-focus-inner,
    input::-moz-focus-inner {
        border: 0;
        padding: 0; }

    input {
        line-height: normal; }

    input[type="checkbox"],
    input[type="radio"] {
        box-sizing: border-box;
        padding: 0; }

    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        height: auto; }

    input[type="search"] {
        -webkit-appearance: textfield;
        box-sizing: content-box; }

    input[type="search"]::-webkit-search-cancel-button,
    input[type="search"]::-webkit-search-decoration {
        -webkit-appearance: none; }

    fieldset {
        border: 1px solid #c0c0c0;
        margin: 0 2px;
        padding: 0.35em 0.625em 0.75em; }

    legend {
        border: 0;
        padding: 0; }

    textarea {
        overflow: auto; }

    optgroup {
        font-weight: bold; }

    table {
        border-collapse: collapse;
        border-spacing: 0; }

    td,
    th {
        padding: 0; }


    @media print {
        *,
        *:before,
        *:after {
            background: transparent !important;
            color: #000 !important;
            box-shadow: none !important;
            text-shadow: none !important; }
        a,
        a:visited {
            text-decoration: underline; }
        a[href]:after {
            content: " (" attr(href) ")"; }
        abbr[title]:after {
            content: " (" attr(title) ")"; }
        a[href^="#"]:after,
        a[href^="javascript:"]:after {
            content: ""; }
        pre,
        blockquote {
            border: 1px solid #999;
            page-break-inside: avoid; }
        thead {
            display: table-header-group; }
        tr,
        img {
            page-break-inside: avoid; }
        img {
            max-width: 100% !important; }
        p,
        h2,
        h3 {
            orphans: 3;
            widows: 3; }
        h2,
        h3 {
            page-break-after: avoid; }
        .navbar {
            display: none; }
        .btn > .caret,
        .dropup > .btn > .caret {
            border-top-color: #000 !important; }
        .label {
            border: 1px solid #000; }
        .table {
            border-collapse: collapse !important; }
        .table td,
        .table th {
            background-color: #fff !important; }
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #ddd !important; } }


    * {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box; }

    *:before,
    *:after {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box; }



    input,
    button,
    select,
    textarea {
        font-family: inherit;
        font-size: inherit;
        line-height: inherit; }

    a {
        color: #4FC1E9;
        text-decoration: none; }
    a:hover, a:focus {
        color: #4fc1e9;
        text-decoration: underline; }
    a:focus {
        outline: thin dotted;
        outline: 5px auto -webkit-focus-ring-color;
        outline-offset: -2px; }

    figure {
        margin: 0; }

    img {
        vertical-align: middle; }

    .img-responsive {
        display: block;
        max-width: 100%;
        height: auto; }

    .img-rounded {
        border-radius: 0; }

    .img-thumbnail {
        padding: 4px;
        line-height: 1.42857;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 0;
        -webkit-transition: all 0.2s ease-in-out;
        -o-transition: all 0.2s ease-in-out;
        transition: all 0.2s ease-in-out;
        display: inline-block;
        max-width: 100%;
        height: auto; }

    .img-circle {
        border-radius: 50%; }

    hr {
        margin-top: 18px;
        margin-bottom: 18px;
        border: 0;
        border-top: 1px solid #eeeeee; }

    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        margin: -1px;
        padding: 0;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        border: 0; }

    .sr-only-focusable:active, .sr-only-focusable:focus {
        position: static;
        width: auto;
        height: auto;
        margin: 0;
        overflow: visible;
        clip: auto; }

    [role="button"] {
        cursor: pointer; }

    h1, h2, h3, h4, h5, h6,
    .h1, .h2, .h3, .h4, .h5, .h6 {
        font-family: inherit;
        font-weight: 500;
        line-height: 1.1;
        color: inherit; }
    h1 small,
    h1 .small, h2 small,
    h2 .small, h3 small,
    h3 .small, h4 small,
    h4 .small, h5 small,
    h5 .small, h6 small,
    h6 .small,
    .h1 small,
    .h1 .small, .h2 small,
    .h2 .small, .h3 small,
    .h3 .small, .h4 small,
    .h4 .small, .h5 small,
    .h5 .small, .h6 small,
    .h6 .small {
        font-weight: normal;
        line-height: 1;
        color: #777777; }

    h1, .h1,
    h2, .h2,
    h3, .h3 {
        margin-top: 18px;
        margin-bottom: 9px; }
    h1 small,
    h1 .small, .h1 small,
    .h1 .small,
    h2 small,
    h2 .small, .h2 small,
    .h2 .small,
    h3 small,
    h3 .small, .h3 small,
    .h3 .small {
        font-size: 65%; }

    h4, .h4,
    h5, .h5,
    h6, .h6 {
        margin-top: 9px;
        margin-bottom: 9px; }
    h4 small,
    h4 .small, .h4 small,
    .h4 .small,
    h5 small,
    h5 .small, .h5 small,
    .h5 .small,
    h6 small,
    h6 .small, .h6 small,
    .h6 .small {
        font-size: 75%; }

    h1, .h1 {
        font-size: 33px; }

    h2, .h2 {
        font-size: 27px; }

    h3, .h3 {
        font-size: 23px; }

    h4, .h4 {
        font-size: 17px; }

    h5, .h5 {
        font-size: 13px; }

    h6, .h6 {
        font-size: 12px; }

    p {
        margin: 0 0 9px; }

    .lead {
        margin-bottom: 18px;
        font-size: 14px;
        font-weight: 300;
        line-height: 1.4; }
    @media (min-width: 768px) {
        .lead {
            font-size: 19.5px; } }

    small,
    .small {
        font-size: 92%; }

    mark,
    .mark {
        background-color: #FAECB2;
        padding: .2em; }

    .text-left {
        text-align: left; }

    .text-right {
        text-align: right; }

    .text-center {
        text-align: center; }

    .text-justify {
        text-align: justify; }

    .text-nowrap {
        white-space: nowrap; }

    .text-lowercase {
        text-transform: lowercase; }

    .text-uppercase, .initialism {
        text-transform: uppercase; }

    .text-capitalize {
        text-transform: capitalize; }

    .text-muted {
        color: #777777; }

    .text-primary {
        color: #4FC1E9; }

    a.text-primary:hover,
    a.text-primary:focus {
        color: #22b1e3; }

    .text-success {
        color: #344628; }

    a.text-success:hover,
    a.text-success:focus {
        color: #1c2615; }

    .text-info {
        color: #165651; }

    a.text-info:hover,
    a.text-info:focus {
        color: #0c2d2b; }

    .text-warning {
        color: #8a6d3b; }

    a.text-warning:hover,
    a.text-warning:focus {
        color: #66512c; }

    .text-danger {
        color: #a94442; }

    a.text-danger:hover,
    a.text-danger:focus {
        color: #843534; }

    .bg-primary {
        color: #fff; }

    .bg-primary {
        background-color: #4FC1E9; }

    a.bg-primary:hover,
    a.bg-primary:focus {
        background-color: #22b1e3; }

    .bg-success {
        background-color: #c8e9b1; }

    a.bg-success:hover,
    a.bg-success:focus {
        background-color: #acde89; }

    .bg-info {
        background-color: #DDF3F1; }

    a.bg-info:hover,
    a.bg-info:focus {
        background-color: #b7e6e1; }

    .bg-warning {
        background-color: #FAECB2; }

    a.bg-warning:hover,
    a.bg-warning:focus {
        background-color: #f7e082; }

    .bg-danger {
        background-color: #FECFB0; }

    a.bg-danger:hover,
    a.bg-danger:focus {
        background-color: #fdb07e; }

    .page-header {
        padding-bottom: 8px;
        margin: 36px 0 18px;
        border-bottom: 1px solid #eeeeee; }

    ul,
    ol {
        margin-top: 0;
        margin-bottom: 9px; }
    ul ul,
    ul ol,
    ol ul,
    ol ol {
        margin-bottom: 0; }

    .list-unstyled {
        padding-left: 0;
        list-style: none; }

    .list-inline {
        padding-left: 0;
        list-style: none;
        margin-left: -5px; }
    .list-inline > li {
        display: inline-block;
        padding-left: 5px;
        padding-right: 5px; }

    dl {
        margin-top: 0;
        margin-bottom: 18px; }

    dt,
    dd {
        line-height: 1.42857; }

    dt {
        font-weight: bold; }

    dd {
        margin-left: 0; }

    .dl-horizontal dd:before, .dl-horizontal dd:after {
        content: " ";
        display: table; }

    .dl-horizontal dd:after {
        clear: both; }

    @media (min-width: 768px) {
        .dl-horizontal dt {
            float: left;
            width: 160px;
            clear: left;
            text-align: right;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap; }
        .dl-horizontal dd {
            margin-left: 180px; } }

    abbr[title],
    abbr[data-original-title] {
        cursor: help;
        border-bottom: 1px dotted #777777; }

    .initialism {
        font-size: 90%; }

    blockquote {
        padding: 9px 18px;
        margin: 0 0 18px;
        font-size: 16.25px;
        border-left: 5px solid #eeeeee; }
    blockquote p:last-child,
    blockquote ul:last-child,
    blockquote ol:last-child {
        margin-bottom: 0; }
    blockquote footer,
    blockquote small,
    blockquote .small {
        display: block;
        font-size: 80%;
        line-height: 1.42857;
        color: #777777; }
    blockquote footer:before,
    blockquote small:before,
    blockquote .small:before {
        content: '\2014 \00A0'; }

    .blockquote-reverse,
    blockquote.pull-right {
        padding-right: 15px;
        padding-left: 0;
        border-right: 5px solid #eeeeee;
        border-left: 0;
        text-align: right; }
    .blockquote-reverse footer:before,
    .blockquote-reverse small:before,
    .blockquote-reverse .small:before,
    blockquote.pull-right footer:before,
    blockquote.pull-right small:before,
    blockquote.pull-right .small:before {
        content: ''; }
    .blockquote-reverse footer:after,
    .blockquote-reverse small:after,
    .blockquote-reverse .small:after,
    blockquote.pull-right footer:after,
    blockquote.pull-right small:after,
    blockquote.pull-right .small:after {
        content: '\00A0 \2014'; }

    address {
        margin-bottom: 18px;
        font-style: normal;
        line-height: 1.42857; }

    code,
    kbd,
    pre,
    samp {
        font-family: Menlo, Monaco, Consolas, "Courier New", monospace; }

    code {
        padding: 2px 4px;
        font-size: 90%;
        color: #c7254e;
        background-color: #f9f2f4;
        border-radius: 0; }

    kbd {
        padding: 2px 4px;
        font-size: 90%;
        color: #fff;
        background-color: #333;
        border-radius: 0;
        box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.25); }
    kbd kbd {
        padding: 0;
        font-size: 100%;
        font-weight: bold;
        box-shadow: none; }

    pre {
        display: block;
        padding: 8.5px;
        margin: 0 0 9px;
        font-size: 12px;
        line-height: 1.42857;
        word-break: break-all;
        word-wrap: break-word;
        color: #333333;
        background-color: #f5f5f5;
        border: 1px solid #ccc;
        border-radius: 0; }
    pre code {
        padding: 0;
        font-size: inherit;
        color: inherit;
        white-space: pre-wrap;
        background-color: transparent;
        border-radius: 0; }

    .pre-scrollable {
        max-height: 340px;
        overflow-y: scroll; }

    .container {
        margin-right: auto;
        margin-left: auto;
        padding-left: 15px;
        padding-right: 15px; }
    .container:before, .container:after {
        content: " ";
        display: table; }
    .container:after {
        clear: both; }
    @media (min-width: 768px) {
        .container {
            width: 750px; } }
    @media (min-width: 992px) {
        .container {
            width: 970px; } }
    @media (min-width: 1200px) {
        .container {
            width: 1170px; } }

    .container-fluid {
        margin-right: auto;
        margin-left: auto;
        padding-left: 15px;
        padding-right: 15px; }
    .container-fluid:before, .container-fluid:after {
        content: " ";
        display: table; }
    .container-fluid:after {
        clear: both; }

    .row {
        margin-left: -15px;
        margin-right: -15px; }
    .row:before, .row:after {
        content: " ";
        display: table; }
    .row:after {
        clear: both; }

    .col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {
        position: relative;
        min-height: 1px;
        padding-left: 15px;
        padding-right: 15px; }

    .col-xs-1, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9, .col-xs-10, .col-xs-11, .col-xs-12 {
        float: left; }

    .col-xs-1 {
        width: 8.33333%; }

    .col-xs-2 {
        width: 16.66667%; }

    .col-xs-3 {
        width: 25%; }

    .col-xs-4 {
        width: 33.33333%; }

    .col-xs-5 {
        width: 41.66667%; }

    .col-xs-6 {
        width: 50%; }

    .col-xs-7 {
        width: 58.33333%; }

    .col-xs-8 {
        width: 66.66667%; }

    .col-xs-9 {
        width: 75%; }

    .col-xs-10 {
        width: 83.33333%; }

    .col-xs-11 {
        width: 91.66667%; }

    .col-xs-12 {
        width: 100%; }

    .col-xs-pull-0 {
        right: auto; }

    .col-xs-pull-1 {
        right: 8.33333%; }

    .col-xs-pull-2 {
        right: 16.66667%; }

    .col-xs-pull-3 {
        right: 25%; }

    .col-xs-pull-4 {
        right: 33.33333%; }

    .col-xs-pull-5 {
        right: 41.66667%; }

    .col-xs-pull-6 {
        right: 50%; }

    .col-xs-pull-7 {
        right: 58.33333%; }

    .col-xs-pull-8 {
        right: 66.66667%; }

    .col-xs-pull-9 {
        right: 75%; }

    .col-xs-pull-10 {
        right: 83.33333%; }

    .col-xs-pull-11 {
        right: 91.66667%; }

    .col-xs-pull-12 {
        right: 100%; }

    .col-xs-push-0 {
        left: auto; }

    .col-xs-push-1 {
        left: 8.33333%; }

    .col-xs-push-2 {
        left: 16.66667%; }

    .col-xs-push-3 {
        left: 25%; }

    .col-xs-push-4 {
        left: 33.33333%; }

    .col-xs-push-5 {
        left: 41.66667%; }

    .col-xs-push-6 {
        left: 50%; }

    .col-xs-push-7 {
        left: 58.33333%; }

    .col-xs-push-8 {
        left: 66.66667%; }

    .col-xs-push-9 {
        left: 75%; }

    .col-xs-push-10 {
        left: 83.33333%; }

    .col-xs-push-11 {
        left: 91.66667%; }

    .col-xs-push-12 {
        left: 100%; }

    .col-xs-offset-0 {
        margin-left: 0%; }

    .col-xs-offset-1 {
        margin-left: 8.33333%; }

    .col-xs-offset-2 {
        margin-left: 16.66667%; }

    .col-xs-offset-3 {
        margin-left: 25%; }

    .col-xs-offset-4 {
        margin-left: 33.33333%; }

    .col-xs-offset-5 {
        margin-left: 41.66667%; }

    .col-xs-offset-6 {
        margin-left: 50%; }

    .col-xs-offset-7 {
        margin-left: 58.33333%; }

    .col-xs-offset-8 {
        margin-left: 66.66667%; }

    .col-xs-offset-9 {
        margin-left: 75%; }

    .col-xs-offset-10 {
        margin-left: 83.33333%; }

    .col-xs-offset-11 {
        margin-left: 91.66667%; }

    .col-xs-offset-12 {
        margin-left: 100%; }

    @media (min-width: 768px) {
        .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
            float: left; }
        .col-sm-1 {
            width: 8.33333%; }
        .col-sm-2 {
            width: 16.66667%; }
        .col-sm-3 {
            width: 25%; }
        .col-sm-4 {
            width: 33.33333%; }
        .col-sm-5 {
            width: 41.66667%; }
        .col-sm-6 {
            width: 50%; }
        .col-sm-7 {
            width: 58.33333%; }
        .col-sm-8 {
            width: 66.66667%; }
        .col-sm-9 {
            width: 75%; }
        .col-sm-10 {
            width: 83.33333%; }
        .col-sm-11 {
            width: 91.66667%; }
        .col-sm-12 {
            width: 100%; }
        .col-sm-pull-0 {
            right: auto; }
        .col-sm-pull-1 {
            right: 8.33333%; }
        .col-sm-pull-2 {
            right: 16.66667%; }
        .col-sm-pull-3 {
            right: 25%; }
        .col-sm-pull-4 {
            right: 33.33333%; }
        .col-sm-pull-5 {
            right: 41.66667%; }
        .col-sm-pull-6 {
            right: 50%; }
        .col-sm-pull-7 {
            right: 58.33333%; }
        .col-sm-pull-8 {
            right: 66.66667%; }
        .col-sm-pull-9 {
            right: 75%; }
        .col-sm-pull-10 {
            right: 83.33333%; }
        .col-sm-pull-11 {
            right: 91.66667%; }
        .col-sm-pull-12 {
            right: 100%; }
        .col-sm-push-0 {
            left: auto; }
        .col-sm-push-1 {
            left: 8.33333%; }
        .col-sm-push-2 {
            left: 16.66667%; }
        .col-sm-push-3 {
            left: 25%; }
        .col-sm-push-4 {
            left: 33.33333%; }
        .col-sm-push-5 {
            left: 41.66667%; }
        .col-sm-push-6 {
            left: 50%; }
        .col-sm-push-7 {
            left: 58.33333%; }
        .col-sm-push-8 {
            left: 66.66667%; }
        .col-sm-push-9 {
            left: 75%; }
        .col-sm-push-10 {
            left: 83.33333%; }
        .col-sm-push-11 {
            left: 91.66667%; }
        .col-sm-push-12 {
            left: 100%; }
        .col-sm-offset-0 {
            margin-left: 0%; }
        .col-sm-offset-1 {
            margin-left: 8.33333%; }
        .col-sm-offset-2 {
            margin-left: 16.66667%; }
        .col-sm-offset-3 {
            margin-left: 25%; }
        .col-sm-offset-4 {
            margin-left: 33.33333%; }
        .col-sm-offset-5 {
            margin-left: 41.66667%; }
        .col-sm-offset-6 {
            margin-left: 50%; }
        .col-sm-offset-7 {
            margin-left: 58.33333%; }
        .col-sm-offset-8 {
            margin-left: 66.66667%; }
        .col-sm-offset-9 {
            margin-left: 75%; }
        .col-sm-offset-10 {
            margin-left: 83.33333%; }
        .col-sm-offset-11 {
            margin-left: 91.66667%; }
        .col-sm-offset-12 {
            margin-left: 100%; } }

    @media (min-width: 992px) {
        .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {
            float: left; }
        .col-md-1 {
            width: 8.33333%; }
        .col-md-2 {
            width: 16.66667%; }
        .col-md-3 {
            width: 25%; }
        .col-md-4 {
            width: 33.33333%; }
        .col-md-5 {
            width: 41.66667%; }
        .col-md-6 {
            width: 50%; }
        .col-md-7 {
            width: 58.33333%; }
        .col-md-8 {
            width: 66.66667%; }
        .col-md-9 {
            width: 75%; }
        .col-md-10 {
            width: 83.33333%; }
        .col-md-11 {
            width: 91.66667%; }
        .col-md-12 {
            width: 100%; }
        .col-md-pull-0 {
            right: auto; }
        .col-md-pull-1 {
            right: 8.33333%; }
        .col-md-pull-2 {
            right: 16.66667%; }
        .col-md-pull-3 {
            right: 25%; }
        .col-md-pull-4 {
            right: 33.33333%; }
        .col-md-pull-5 {
            right: 41.66667%; }
        .col-md-pull-6 {
            right: 50%; }
        .col-md-pull-7 {
            right: 58.33333%; }
        .col-md-pull-8 {
            right: 66.66667%; }
        .col-md-pull-9 {
            right: 75%; }
        .col-md-pull-10 {
            right: 83.33333%; }
        .col-md-pull-11 {
            right: 91.66667%; }
        .col-md-pull-12 {
            right: 100%; }
        .col-md-push-0 {
            left: auto; }
        .col-md-push-1 {
            left: 8.33333%; }
        .col-md-push-2 {
            left: 16.66667%; }
        .col-md-push-3 {
            left: 25%; }
        .col-md-push-4 {
            left: 33.33333%; }
        .col-md-push-5 {
            left: 41.66667%; }
        .col-md-push-6 {
            left: 50%; }
        .col-md-push-7 {
            left: 58.33333%; }
        .col-md-push-8 {
            left: 66.66667%; }
        .col-md-push-9 {
            left: 75%; }
        .col-md-push-10 {
            left: 83.33333%; }
        .col-md-push-11 {
            left: 91.66667%; }
        .col-md-push-12 {
            left: 100%; }
        .col-md-offset-0 {
            margin-left: 0%; }
        .col-md-offset-1 {
            margin-left: 8.33333%; }
        .col-md-offset-2 {
            margin-left: 16.66667%; }
        .col-md-offset-3 {
            margin-left: 25%; }
        .col-md-offset-4 {
            margin-left: 33.33333%; }
        .col-md-offset-5 {
            margin-left: 41.66667%; }
        .col-md-offset-6 {
            margin-left: 50%; }
        .col-md-offset-7 {
            margin-left: 58.33333%; }
        .col-md-offset-8 {
            margin-left: 66.66667%; }
        .col-md-offset-9 {
            margin-left: 75%; }
        .col-md-offset-10 {
            margin-left: 83.33333%; }
        .col-md-offset-11 {
            margin-left: 91.66667%; }
        .col-md-offset-12 {
            margin-left: 100%; } }

    @media (min-width: 1200px) {
        .col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12 {
            float: left; }
        .col-lg-1 {
            width: 8.33333%; }
        .col-lg-2 {
            width: 16.66667%; }
        .col-lg-3 {
            width: 25%; }
        .col-lg-4 {
            width: 33.33333%; }
        .col-lg-5 {
            width: 41.66667%; }
        .col-lg-6 {
            width: 50%; }
        .col-lg-7 {
            width: 58.33333%; }
        .col-lg-8 {
            width: 66.66667%; }
        .col-lg-9 {
            width: 75%; }
        .col-lg-10 {
            width: 83.33333%; }
        .col-lg-11 {
            width: 91.66667%; }
        .col-lg-12 {
            width: 100%; }
        .col-lg-pull-0 {
            right: auto; }
        .col-lg-pull-1 {
            right: 8.33333%; }
        .col-lg-pull-2 {
            right: 16.66667%; }
        .col-lg-pull-3 {
            right: 25%; }
        .col-lg-pull-4 {
            right: 33.33333%; }
        .col-lg-pull-5 {
            right: 41.66667%; }
        .col-lg-pull-6 {
            right: 50%; }
        .col-lg-pull-7 {
            right: 58.33333%; }
        .col-lg-pull-8 {
            right: 66.66667%; }
        .col-lg-pull-9 {
            right: 75%; }
        .col-lg-pull-10 {
            right: 83.33333%; }
        .col-lg-pull-11 {
            right: 91.66667%; }
        .col-lg-pull-12 {
            right: 100%; }
        .col-lg-push-0 {
            left: auto; }
        .col-lg-push-1 {
            left: 8.33333%; }
        .col-lg-push-2 {
            left: 16.66667%; }
        .col-lg-push-3 {
            left: 25%; }
        .col-lg-push-4 {
            left: 33.33333%; }
        .col-lg-push-5 {
            left: 41.66667%; }
        .col-lg-push-6 {
            left: 50%; }
        .col-lg-push-7 {
            left: 58.33333%; }
        .col-lg-push-8 {
            left: 66.66667%; }
        .col-lg-push-9 {
            left: 75%; }
        .col-lg-push-10 {
            left: 83.33333%; }
        .col-lg-push-11 {
            left: 91.66667%; }
        .col-lg-push-12 {
            left: 100%; }
        .col-lg-offset-0 {
            margin-left: 0%; }
        .col-lg-offset-1 {
            margin-left: 8.33333%; }
        .col-lg-offset-2 {
            margin-left: 16.66667%; }
        .col-lg-offset-3 {
            margin-left: 25%; }
        .col-lg-offset-4 {
            margin-left: 33.33333%; }
        .col-lg-offset-5 {
            margin-left: 41.66667%; }
        .col-lg-offset-6 {
            margin-left: 50%; }
        .col-lg-offset-7 {
            margin-left: 58.33333%; }
        .col-lg-offset-8 {
            margin-left: 66.66667%; }
        .col-lg-offset-9 {
            margin-left: 75%; }
        .col-lg-offset-10 {
            margin-left: 83.33333%; }
        .col-lg-offset-11 {
            margin-left: 91.66667%; }
        .col-lg-offset-12 {
            margin-left: 100%; } }

    table {
        background-color: transparent; }

    caption {
        padding-top: 8px;
        padding-bottom: 8px;
        color: #777777;
        text-align: left; }

    th {
        text-align: left; }

    .table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 18px; }
    .table > thead > tr > th,
    .table > thead > tr > td,
    .table > tbody > tr > th,
    .table > tbody > tr > td,
    .table > tfoot > tr > th,
    .table > tfoot > tr > td {
        padding: 8px;
        line-height: 1.42857;
        vertical-align: top;
        border-top: 1px solid #ddd; }
    .table > thead > tr > th {
        vertical-align: bottom;
        border-bottom: 2px solid #ddd; }
    .table > caption + thead > tr:first-child > th,
    .table > caption + thead > tr:first-child > td,
    .table > colgroup + thead > tr:first-child > th,
    .table > colgroup + thead > tr:first-child > td,
    .table > thead:first-child > tr:first-child > th,
    .table > thead:first-child > tr:first-child > td {
        border-top: 0; }
    .table > tbody + tbody {
        border-top: 2px solid #ddd; }
    .table .table {
        background-color: #fff; }

    .table-condensed > thead > tr > th,
    .table-condensed > thead > tr > td,
    .table-condensed > tbody > tr > th,
    .table-condensed > tbody > tr > td,
    .table-condensed > tfoot > tr > th,
    .table-condensed > tfoot > tr > td {
        padding: 5px; }

    .table-bordered {
        border: 1px solid #ddd; }
    .table-bordered > thead > tr > th,
    .table-bordered > thead > tr > td,
    .table-bordered > tbody > tr > th,
    .table-bordered > tbody > tr > td,
    .table-bordered > tfoot > tr > th,
    .table-bordered > tfoot > tr > td {
        border: 1px solid #ddd; }
    .table-bordered > thead > tr > th,
    .table-bordered > thead > tr > td {
        border-bottom-width: 2px; }

    .table-striped > tbody > tr:nth-of-type(odd) {
        background-color: #f9f9f9; }

    .table-hover > tbody > tr:hover {
        background-color: #f5f5f5; }

    table col[class*="col-"] {
        position: static;
        float: none;
        display: table-column; }

    table td[class*="col-"],
    table th[class*="col-"] {
        position: static;
        float: none;
        display: table-cell; }

    .table > thead > tr > td.active,
    .table > thead > tr > th.active,
    .table > thead > tr.active > td,
    .table > thead > tr.active > th,
    .table > tbody > tr > td.active,
    .table > tbody > tr > th.active,
    .table > tbody > tr.active > td,
    .table > tbody > tr.active > th,
    .table > tfoot > tr > td.active,
    .table > tfoot > tr > th.active,
    .table > tfoot > tr.active > td,
    .table > tfoot > tr.active > th {
        background-color: #f5f5f5; }

    .table-hover > tbody > tr > td.active:hover,
    .table-hover > tbody > tr > th.active:hover,
    .table-hover > tbody > tr.active:hover > td,
    .table-hover > tbody > tr:hover > .active,
    .table-hover > tbody > tr.active:hover > th {
        background-color: #e8e8e8; }

    .table > thead > tr > td.success,
    .table > thead > tr > th.success,
    .table > thead > tr.success > td,
    .table > thead > tr.success > th,
    .table > tbody > tr > td.success,
    .table > tbody > tr > th.success,
    .table > tbody > tr.success > td,
    .table > tbody > tr.success > th,
    .table > tfoot > tr > td.success,
    .table > tfoot > tr > th.success,
    .table > tfoot > tr.success > td,
    .table > tfoot > tr.success > th {
        background-color: #c8e9b1; }

    .table-hover > tbody > tr > td.success:hover,
    .table-hover > tbody > tr > th.success:hover,
    .table-hover > tbody > tr.success:hover > td,
    .table-hover > tbody > tr:hover > .success,
    .table-hover > tbody > tr.success:hover > th {
        background-color: #bae39d; }

    .table > thead > tr > td.info,
    .table > thead > tr > th.info,
    .table > thead > tr.info > td,
    .table > thead > tr.info > th,
    .table > tbody > tr > td.info,
    .table > tbody > tr > th.info,
    .table > tbody > tr.info > td,
    .table > tbody > tr.info > th,
    .table > tfoot > tr > td.info,
    .table > tfoot > tr > th.info,
    .table > tfoot > tr.info > td,
    .table > tfoot > tr.info > th {
        background-color: #DDF3F1; }

    .table-hover > tbody > tr > td.info:hover,
    .table-hover > tbody > tr > th.info:hover,
    .table-hover > tbody > tr.info:hover > td,
    .table-hover > tbody > tr:hover > .info,
    .table-hover > tbody > tr.info:hover > th {
        background-color: #caece9; }

    .table > thead > tr > td.warning,
    .table > thead > tr > th.warning,
    .table > thead > tr.warning > td,
    .table > thead > tr.warning > th,
    .table > tbody > tr > td.warning,
    .table > tbody > tr > th.warning,
    .table > tbody > tr.warning > td,
    .table > tbody > tr.warning > th,
    .table > tfoot > tr > td.warning,
    .table > tfoot > tr > th.warning,
    .table > tfoot > tr.warning > td,
    .table > tfoot > tr.warning > th {
        background-color: #FAECB2; }

    .table-hover > tbody > tr > td.warning:hover,
    .table-hover > tbody > tr > th.warning:hover,
    .table-hover > tbody > tr.warning:hover > td,
    .table-hover > tbody > tr:hover > .warning,
    .table-hover > tbody > tr.warning:hover > th {
        background-color: #f8e69a; }

    .table > thead > tr > td.danger,
    .table > thead > tr > th.danger,
    .table > thead > tr.danger > td,
    .table > thead > tr.danger > th,
    .table > tbody > tr > td.danger,
    .table > tbody > tr > th.danger,
    .table > tbody > tr.danger > td,
    .table > tbody > tr.danger > th,
    .table > tfoot > tr > td.danger,
    .table > tfoot > tr > th.danger,
    .table > tfoot > tr.danger > td,
    .table > tfoot > tr.danger > th {
        background-color: #FECFB0; }

    .table-hover > tbody > tr > td.danger:hover,
    .table-hover > tbody > tr > th.danger:hover,
    .table-hover > tbody > tr.danger:hover > td,
    .table-hover > tbody > tr:hover > .danger,
    .table-hover > tbody > tr.danger:hover > th {
        background-color: #fec097; }

    .table-responsive {
        overflow-x: auto;
        min-height: 0.01%; }
    @media screen and (max-width: 767px) {
        .table-responsive {
            width: 100%;
            margin-bottom: 13.5px;
            overflow-y: hidden;
            -ms-overflow-style: -ms-autohiding-scrollbar;
            border: 1px solid #ddd; }
        .table-responsive > .table {
            margin-bottom: 0; }
        .table-responsive > .table > thead > tr > th,
        .table-responsive > .table > thead > tr > td,
        .table-responsive > .table > tbody > tr > th,
        .table-responsive > .table > tbody > tr > td,
        .table-responsive > .table > tfoot > tr > th,
        .table-responsive > .table > tfoot > tr > td {
            white-space: nowrap; }
        .table-responsive > .table-bordered {
            border: 0; }
        .table-responsive > .table-bordered > thead > tr > th:first-child,
        .table-responsive > .table-bordered > thead > tr > td:first-child,
        .table-responsive > .table-bordered > tbody > tr > th:first-child,
        .table-responsive > .table-bordered > tbody > tr > td:first-child,
        .table-responsive > .table-bordered > tfoot > tr > th:first-child,
        .table-responsive > .table-bordered > tfoot > tr > td:first-child {
            border-left: 0; }
        .table-responsive > .table-bordered > thead > tr > th:last-child,
        .table-responsive > .table-bordered > thead > tr > td:last-child,
        .table-responsive > .table-bordered > tbody > tr > th:last-child,
        .table-responsive > .table-bordered > tbody > tr > td:last-child,
        .table-responsive > .table-bordered > tfoot > tr > th:last-child,
        .table-responsive > .table-bordered > tfoot > tr > td:last-child {
            border-right: 0; }
        .table-responsive > .table-bordered > tbody > tr:last-child > th,
        .table-responsive > .table-bordered > tbody > tr:last-child > td,
        .table-responsive > .table-bordered > tfoot > tr:last-child > th,
        .table-responsive > .table-bordered > tfoot > tr:last-child > td {
            border-bottom: 0; } }

    fieldset {
        padding: 0;
        margin: 0;
        border: 0;
        min-width: 0; }

    legend {
        display: block;
        width: 100%;
        padding: 0;
        margin-bottom: 18px;
        font-size: 19.5px;
        line-height: inherit;
        color: #333333;
        border: 0;
        border-bottom: 1px solid #e5e5e5; }

    label {
        display: inline-block;
        max-width: 100%;
        margin-bottom: 5px;
        font-weight: bold; }

    input[type="search"] {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box; }

    input[type="radio"],
    input[type="checkbox"] {
        margin: 4px 0 0;
        margin-top: 1px \9;
        line-height: normal; }

    input[type="file"] {
        display: block; }

    input[type="range"] {
        display: block;
        width: 100%; }

    select[multiple],
    select[size] {
        height: auto; }

    input[type="file"]:focus,
    input[type="radio"]:focus,
    input[type="checkbox"]:focus {
        outline: thin dotted;
        outline: 5px auto -webkit-focus-ring-color;
        outline-offset: -2px; }

    output {
        display: block;
        padding-top: 7px;
        font-size: 13px;
        line-height: 1.42857;
        color: #555555; }

    .form-control {
        display: block;
        width: 100%;
        height: 32px;
        padding: 6px 12px;
        font-size: 13px;
        line-height: 1.42857;
        color: #555555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 0;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
        -webkit-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
        -o-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
        transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s; }
    .form-control:focus {
        border-color: #66afe9;
        outline: 0;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(102, 175, 233, 0.6);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(102, 175, 233, 0.6); }
    .form-control::-moz-placeholder {
        color: #999;
        opacity: 1; }
    .form-control:-ms-input-placeholder {
        color: #999; }
    .form-control::-webkit-input-placeholder {
        color: #999; }
    .form-control::-ms-expand {
        border: 0;
        background-color: transparent; }
    .form-control[disabled], .form-control[readonly],
    fieldset[disabled] .form-control {
        background-color: #eeeeee;
        opacity: 1; }
    .form-control[disabled],
    fieldset[disabled] .form-control {
        cursor: not-allowed; }

    textarea.form-control {
        height: auto; }

    input[type="search"] {
        -webkit-appearance: none; }


    .form-group {
        margin-bottom: 15px; }

    .radio,
    .checkbox {
        position: relative;
        display: block;
        margin-top: 10px;
        margin-bottom: 10px; }
    .radio label,
    .checkbox label {
        min-height: 18px;
        padding-left: 20px;
        margin-bottom: 0;
        font-weight: normal;
        cursor: pointer; }

    .radio input[type="radio"],
    .radio-inline input[type="radio"],
    .checkbox input[type="checkbox"],
    .checkbox-inline input[type="checkbox"] {
        position: absolute;
        margin-left: -20px;
        margin-top: 4px \9; }

    .radio + .radio,
    .checkbox + .checkbox {
        margin-top: -5px; }

    .radio-inline,
    .checkbox-inline {
        position: relative;
        display: inline-block;
        padding-left: 20px;
        margin-bottom: 0;
        vertical-align: middle;
        font-weight: normal;
        cursor: pointer; }

    .radio-inline + .radio-inline,
    .checkbox-inline + .checkbox-inline {
        margin-top: 0;
        margin-left: 10px; }

    input[type="radio"][disabled], input[type="radio"].disabled,
    fieldset[disabled] input[type="radio"],
    input[type="checkbox"][disabled],
    input[type="checkbox"].disabled,
    fieldset[disabled]
    input[type="checkbox"] {
        cursor: not-allowed; }

    .radio-inline.disabled,
    fieldset[disabled] .radio-inline,
    .checkbox-inline.disabled,
    fieldset[disabled]
.checkbox-inline {
        cursor: not-allowed; }

    .radio.disabled label,
    fieldset[disabled] .radio label,
    .checkbox.disabled label,
    fieldset[disabled]
.checkbox label {
        cursor: not-allowed; }

    .form-control-static {
        padding-top: 7px;
        padding-bottom: 7px;
        margin-bottom: 0;
        min-height: 31px; }
    .form-control-static.input-lg, .input-group-lg > .form-control-static.form-control,
    .input-group-lg > .form-control-static.input-group-addon,
    .input-group-lg > .input-group-btn > .form-control-static.btn, .form-control-static.input-sm, .input-group-sm > .form-control-static.form-control,
    .input-group-sm > .form-control-static.input-group-addon,
    .input-group-sm > .input-group-btn > .form-control-static.btn {
        padding-left: 0;
        padding-right: 0; }

    .input-sm, .input-group-sm > .form-control,
    .input-group-sm > .input-group-addon,
    .input-group-sm > .input-group-btn > .btn {
        height: 30px;
        padding: 5px 10px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 0; }

    select.input-sm, .input-group-sm > select.form-control,
    .input-group-sm > select.input-group-addon,
    .input-group-sm > .input-group-btn > select.btn {
        height: 30px;
        line-height: 30px; }

    textarea.input-sm, .input-group-sm > textarea.form-control,
    .input-group-sm > textarea.input-group-addon,
    .input-group-sm > .input-group-btn > textarea.btn,
    select[multiple].input-sm,
    .input-group-sm > select[multiple].form-control,
    .input-group-sm > select[multiple].input-group-addon,
    .input-group-sm > .input-group-btn > select[multiple].btn {
        height: auto; }

    .form-group-sm .form-control {
        height: 30px;
        padding: 5px 10px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 0; }

    .form-group-sm select.form-control {
        height: 30px;
        line-height: 30px; }

    .form-group-sm textarea.form-control,
    .form-group-sm select[multiple].form-control {
        height: auto; }

    .form-group-sm .form-control-static {
        height: 30px;
        min-height: 30px;
        padding: 6px 10px;
        font-size: 12px;
        line-height: 1.5; }

    .input-lg, .input-group-lg > .form-control,
    .input-group-lg > .input-group-addon,
    .input-group-lg > .input-group-btn > .btn {
        height: 45px;
        padding: 10px 16px;
        font-size: 17px;
        line-height: 1.33333;
        border-radius: 0; }

    select.input-lg, .input-group-lg > select.form-control,
    .input-group-lg > select.input-group-addon,
    .input-group-lg > .input-group-btn > select.btn {
        height: 45px;
        line-height: 45px; }

    textarea.input-lg, .input-group-lg > textarea.form-control,
    .input-group-lg > textarea.input-group-addon,
    .input-group-lg > .input-group-btn > textarea.btn,
    select[multiple].input-lg,
    .input-group-lg > select[multiple].form-control,
    .input-group-lg > select[multiple].input-group-addon,
    .input-group-lg > .input-group-btn > select[multiple].btn {
        height: auto; }

    .form-group-lg .form-control {
        height: 45px;
        padding: 10px 16px;
        font-size: 17px;
        line-height: 1.33333;
        border-radius: 0; }

    .form-group-lg select.form-control {
        height: 45px;
        line-height: 45px; }

    .form-group-lg textarea.form-control,
    .form-group-lg select[multiple].form-control {
        height: auto; }

    .form-group-lg .form-control-static {
        height: 45px;
        min-height: 35px;
        padding: 11px 16px;
        font-size: 17px;
        line-height: 1.33333; }

    .has-feedback {
        position: relative; }
    .has-feedback .form-control {
        padding-right: 40px; }

    .form-control-feedback {
        position: absolute;
        top: 0;
        right: 0;
        z-index: 2;
        display: block;
        width: 32px;
        height: 32px;
        line-height: 32px;
        text-align: center;
        pointer-events: none; }

    .input-lg + .form-control-feedback, .input-group-lg > .form-control + .form-control-feedback,
    .input-group-lg > .input-group-addon + .form-control-feedback,
    .input-group-lg > .input-group-btn > .btn + .form-control-feedback,
    .input-group-lg + .form-control-feedback,
    .form-group-lg .form-control + .form-control-feedback {
        width: 45px;
        height: 45px;
        line-height: 45px; }

    .input-sm + .form-control-feedback, .input-group-sm > .form-control + .form-control-feedback,
    .input-group-sm > .input-group-addon + .form-control-feedback,
    .input-group-sm > .input-group-btn > .btn + .form-control-feedback,
    .input-group-sm + .form-control-feedback,
    .form-group-sm .form-control + .form-control-feedback {
        width: 30px;
        height: 30px;
        line-height: 30px; }



    .btn {
        display: inline-block;
        margin-bottom: 0;
        font-weight: normal;
        text-align: center;
        vertical-align: middle;
        touch-action: manipulation;
        cursor: pointer;
        background-image: none;
        border: 1px solid transparent;
        white-space: nowrap;
        padding: 6px 12px;
        font-size: 13px;
        line-height: 1.42857;
        border-radius: 0;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none; }


    .btn-default {
        color: #333;
        background-color: #fff;
        border-color: #ccc; }


    .media-right,
    .media > .pull-right {
        padding-left: 10px; }

    .media-left,
    .media > .pull-left {
        padding-right: 10px; }

    .media-left,
    .media-right,
    .media-body {
        display: table-cell;
        vertical-align: top; }

    .media-middle {
        vertical-align: middle; }

    .media-bottom {
        vertical-align: bottom; }

    .media-heading {
        margin-top: 0;
        margin-bottom: 5px; }

    .media-list {
        padding-left: 0;
        list-style: none; }

    .list-group {
        margin-bottom: 20px;
        padding-left: 0; }

    .list-group-item {
        position: relative;
        display: block;
        padding: 10px 15px;
        margin-bottom: -1px;
        background-color: #fff;
        border: 1px solid #ddd; }
    .list-group-item:first-child {
        border-top-right-radius: 0;
        border-top-left-radius: 0; }
    .list-group-item:last-child {
        margin-bottom: 0;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0; }

    a.list-group-item,
    button.list-group-item {
        color: #555; }
    a.list-group-item .list-group-item-heading,
    button.list-group-item .list-group-item-heading {
        color: #333; }
    a.list-group-item:hover, a.list-group-item:focus,
    button.list-group-item:hover,
    button.list-group-item:focus {
        text-decoration: none;
        color: #555;
        background-color: #f5f5f5; }

    button.list-group-item {
        width: 100%;
        text-align: left; }

    .list-group-item.disabled, .list-group-item.disabled:hover, .list-group-item.disabled:focus {
        background-color: #eeeeee;
        color: #777777;
        cursor: not-allowed; }
    .list-group-item.disabled .list-group-item-heading, .list-group-item.disabled:hover .list-group-item-heading, .list-group-item.disabled:focus .list-group-item-heading {
        color: inherit; }
    .list-group-item.disabled .list-group-item-text, .list-group-item.disabled:hover .list-group-item-text, .list-group-item.disabled:focus .list-group-item-text {
        color: #777777; }

    .list-group-item.active, .list-group-item.active:hover, .list-group-item.active:focus {
        z-index: 2;
        color: #fff;
        background-color: #4FC1E9;
        border-color: #4FC1E9; }
    .list-group-item.active .list-group-item-heading,
    .list-group-item.active .list-group-item-heading > small,
    .list-group-item.active .list-group-item-heading > .small, .list-group-item.active:hover .list-group-item-heading,
    .list-group-item.active:hover .list-group-item-heading > small,
    .list-group-item.active:hover .list-group-item-heading > .small, .list-group-item.active:focus .list-group-item-heading,
    .list-group-item.active:focus .list-group-item-heading > small,
    .list-group-item.active:focus .list-group-item-heading > .small {
        color: inherit; }
    .list-group-item.active .list-group-item-text, .list-group-item.active:hover .list-group-item-text, .list-group-item.active:focus .list-group-item-text {
        color: white; }

    .list-group-item-success {
        color: #344628;
        background-color: #c8e9b1; }

    a.list-group-item-success,
    button.list-group-item-success {
        color: #344628; }
    a.list-group-item-success .list-group-item-heading,
    button.list-group-item-success .list-group-item-heading {
        color: inherit; }
    a.list-group-item-success:hover, a.list-group-item-success:focus,
    button.list-group-item-success:hover,
    button.list-group-item-success:focus {
        color: #344628;
        background-color: #bae39d; }
    a.list-group-item-success.active, a.list-group-item-success.active:hover, a.list-group-item-success.active:focus,
    button.list-group-item-success.active,
    button.list-group-item-success.active:hover,
    button.list-group-item-success.active:focus {
        color: #fff;
        background-color: #344628;
        border-color: #344628; }

    .list-group-item-info {
        color: #165651;
        background-color: #DDF3F1; }

    a.list-group-item-info,
    button.list-group-item-info {
        color: #165651; }
    a.list-group-item-info .list-group-item-heading,
    button.list-group-item-info .list-group-item-heading {
        color: inherit; }
    a.list-group-item-info:hover, a.list-group-item-info:focus,
    button.list-group-item-info:hover,
    button.list-group-item-info:focus {
        color: #165651;
        background-color: #caece9; }
    a.list-group-item-info.active, a.list-group-item-info.active:hover, a.list-group-item-info.active:focus,
    button.list-group-item-info.active,
    button.list-group-item-info.active:hover,
    button.list-group-item-info.active:focus {
        color: #fff;
        background-color: #165651;
        border-color: #165651; }

    .list-group-item-warning {
        color: #8a6d3b;
        background-color: #FAECB2; }

    a.list-group-item-warning,
    button.list-group-item-warning {
        color: #8a6d3b; }
    a.list-group-item-warning .list-group-item-heading,
    button.list-group-item-warning .list-group-item-heading {
        color: inherit; }
    a.list-group-item-warning:hover, a.list-group-item-warning:focus,
    button.list-group-item-warning:hover,
    button.list-group-item-warning:focus {
        color: #8a6d3b;
        background-color: #f8e69a; }
    a.list-group-item-warning.active, a.list-group-item-warning.active:hover, a.list-group-item-warning.active:focus,
    button.list-group-item-warning.active,
    button.list-group-item-warning.active:hover,
    button.list-group-item-warning.active:focus {
        color: #fff;
        background-color: #8a6d3b;
        border-color: #8a6d3b; }

    .list-group-item-danger {
        color: #a94442;
        background-color: #FECFB0; }

    a.list-group-item-danger,
    button.list-group-item-danger {
        color: #a94442; }
    a.list-group-item-danger .list-group-item-heading,
    button.list-group-item-danger .list-group-item-heading {
        color: inherit; }
    a.list-group-item-danger:hover, a.list-group-item-danger:focus,
    button.list-group-item-danger:hover,
    button.list-group-item-danger:focus {
        color: #a94442;
        background-color: #fec097; }
    a.list-group-item-danger.active, a.list-group-item-danger.active:hover, a.list-group-item-danger.active:focus,
    button.list-group-item-danger.active,
    button.list-group-item-danger.active:hover,
    button.list-group-item-danger.active:focus {
        color: #fff;
        background-color: #a94442;
        border-color: #a94442; }

    .list-group-item-heading {
        margin-top: 0;
        margin-bottom: 5px; }

    .list-group-item-text {
        margin-bottom: 0;
        line-height: 1.3; }

    .panel {
        margin-bottom: 18px;
        background-color: #fff;
        border: 1px solid transparent;
        border-radius: 0;
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05); }

    .panel-body {
        padding: 15px; }
    .panel-body:before, .panel-body:after {
        content: " ";
        display: table; }
    .panel-body:after {
        clear: both; }

    .panel-heading {
        padding: 10px 15px;
        border-bottom: 1px solid transparent;
        border-top-right-radius: -1;
        border-top-left-radius: -1; }
    .panel-heading > .dropdown .dropdown-toggle {
        color: inherit; }

    .panel-title {
        margin-top: 0;
        margin-bottom: 0;
        font-size: 15px;
        color: inherit; }
    .panel-title > a,
    .panel-title > small,
    .panel-title > .small,
    .panel-title > small > a,
    .panel-title > .small > a {
        color: inherit; }



    .clearfix:before, .clearfix:after {
        content: " ";
        display: table; }

    .clearfix:after {
        clear: both; }

    .center-block {
        display: block;
        margin-left: auto;
        margin-right: auto; }

    .pull-right {
        float: right !important; }

    .pull-left {
        float: left !important; }

    .hide {
        display: none !important; }

    .show {
        display: block !important; }

    .invisible {
        visibility: hidden; }

    .text-hide {
        font: 0/0 a;
        color: transparent;
        text-shadow: none;
        background-color: transparent;
        border: 0; }

    .hidden {
        display: none !important; }

    .affix {
        position: fixed; }

    @-ms-viewport {
        width: device-width; }

    .visible-xs {
        display: none !important; }

    .visible-sm {
        display: none !important; }

    .visible-md {
        display: none !important; }

    .visible-lg {
        display: none !important; }

    .visible-xs-block,
    .visible-xs-inline,
    .visible-xs-inline-block,
    .visible-sm-block,
    .visible-sm-inline,
    .visible-sm-inline-block,
    .visible-md-block,
    .visible-md-inline,
    .visible-md-inline-block,
    .visible-lg-block,
    .visible-lg-inline,
    .visible-lg-inline-block {
        display: none !important; }

    @media (max-width: 767px) {
        .visible-xs {
            display: block !important; }
        table.visible-xs {
            display: table !important; }
        tr.visible-xs {
            display: table-row !important; }
        th.visible-xs,
        td.visible-xs {
            display: table-cell !important; } }

    @media (max-width: 767px) {
        .visible-xs-block {
            display: block !important; } }

    @media (max-width: 767px) {
        .visible-xs-inline {
            display: inline !important; } }

    @media (max-width: 767px) {
        .visible-xs-inline-block {
            display: inline-block !important; } }

    @media (min-width: 768px) and (max-width: 991px) {
        .visible-sm {
            display: block !important; }
        table.visible-sm {
            display: table !important; }
        tr.visible-sm {
            display: table-row !important; }
        th.visible-sm,
        td.visible-sm {
            display: table-cell !important; } }

    @media (min-width: 768px) and (max-width: 991px) {
        .visible-sm-block {
            display: block !important; } }

    @media (min-width: 768px) and (max-width: 991px) {
        .visible-sm-inline {
            display: inline !important; } }

    @media (min-width: 768px) and (max-width: 991px) {
        .visible-sm-inline-block {
            display: inline-block !important; } }

    @media (min-width: 992px) and (max-width: 1199px) {
        .visible-md {
            display: block !important; }
        table.visible-md {
            display: table !important; }
        tr.visible-md {
            display: table-row !important; }
        th.visible-md,
        td.visible-md {
            display: table-cell !important; } }

    @media (min-width: 992px) and (max-width: 1199px) {
        .visible-md-block {
            display: block !important; } }

    @media (min-width: 992px) and (max-width: 1199px) {
        .visible-md-inline {
            display: inline !important; } }

    @media (min-width: 992px) and (max-width: 1199px) {
        .visible-md-inline-block {
            display: inline-block !important; } }

    @media (min-width: 1200px) {
        .visible-lg {
            display: block !important; }
        table.visible-lg {
            display: table !important; }
        tr.visible-lg {
            display: table-row !important; }
        th.visible-lg,
        td.visible-lg {
            display: table-cell !important; } }

    @media (min-width: 1200px) {
        .visible-lg-block {
            display: block !important; } }

    @media (min-width: 1200px) {
        .visible-lg-inline {
            display: inline !important; } }

    @media (min-width: 1200px) {
        .visible-lg-inline-block {
            display: inline-block !important; } }

    @media (max-width: 767px) {
        .hidden-xs {
            display: none !important; } }

    @media (min-width: 768px) and (max-width: 991px) {
        .hidden-sm {
            display: none !important; } }

    @media (min-width: 992px) and (max-width: 1199px) {
        .hidden-md {
            display: none !important; } }

    @media (min-width: 1200px) {
        .hidden-lg {
            display: none !important; } }

    .visible-print {
        display: none !important; }

    @media print {
        .visible-print {
            display: block !important; }
        table.visible-print {
            display: table !important; }
        tr.visible-print {
            display: table-row !important; }
        th.visible-print,
        td.visible-print {
            display: table-cell !important; } }

    .visible-print-block {
        display: none !important; }
    @media print {
        .visible-print-block {
            display: block !important; } }

    .visible-print-inline {
        display: none !important; }
    @media print {
        .visible-print-inline {
            display: inline !important; } }

    .visible-print-inline-block {
        display: none !important; }
    @media print {
        .visible-print-inline-block {
            display: inline-block !important; } }

    @media print {
        .hidden-print {
            display: none !important; } }

    h1, h2, h3, h4, h5, h6,
    .h1, .h2, .h3, .h4, .h5, .h6 {
        font-family: inherit;
        font-weight: 500;
        line-height: 1.1;
        color: inherit; }
    h1 small,
    h1 .small, h2 small,
    h2 .small, h3 small,
    h3 .small, h4 small,
    h4 .small, h5 small,
    h5 .small, h6 small,
    h6 .small,
    .h1 small,
    .h1 .small, .h2 small,
    .h2 .small, .h3 small,
    .h3 .small, .h4 small,
    .h4 .small, .h5 small,
    .h5 .small, .h6 small,
    .h6 .small {
        font-weight: normal;
        line-height: 1;
        color: #777777; }

    h1, .h1,
    h2, .h2,
    h3, .h3 {
        margin-top: 18px;
        margin-bottom: 9px; }
    h1 small,
    h1 .small, .h1 small,
    .h1 .small,
    h2 small,
    h2 .small, .h2 small,
    .h2 .small,
    h3 small,
    h3 .small, .h3 small,
    .h3 .small {
        font-size: 65%; }

    h4, .h4,
    h5, .h5,
    h6, .h6 {
        margin-top: 9px;
        margin-bottom: 9px; }
    h4 small,
    h4 .small, .h4 small,
    .h4 .small,
    h5 small,
    h5 .small, .h5 small,
    .h5 .small,
    h6 small,
    h6 .small, .h6 small,
    .h6 .small {
        font-size: 75%; }

    h1, .h1 {
        font-size: 33px; }

    h2, .h2 {
        font-size: 27px; }

    h3, .h3 {
        font-size: 23px; }

    h4, .h4 {
        font-size: 17px; }

    h5, .h5 {
        font-size: 13px; }

    h6, .h6 {
        font-size: 12px; }

    p {
        margin: 0 0 9px; }




</style>
<body>
<div class="row" >
    <small> Archive des emails Najda Assistance - [ Envoi ]</small>
</div>
<div class="row" >
    <div class="col-lg-2 " >
    </div>
    <div class="col-lg-8 " style="padding-top:30px">

        <div class="panel-heading" style="   background: #d6eef7!important;">
            <div class="row">
                <div class="col-md-3 pull-left">
                    @if (!empty($envoye->dossier))
                        <button class="btn btn-sm btn-default"><b>REF: {{ $envoye['dossier']   }}</b></button>
                    @endif
                </div>
                <div class="col-md-6 pull-left">
                </div>
                <div class="col-md-3 pull-right">
                    <span><b>Date: </b><?php if ($envoye['type']=='email'){echo  date('d/m/Y H:i', strtotime( $envoye['reception']  )) ; }else {echo  date('d/m/Y H:i', strtotime( $envoye['created_at']  )) ; }?></span>

                </div>

            </div>



        </div>



        <div id="emailhead" class="panel-collapse"  style="   ">
            <div class="panel-body">

                <div class="row" style="padding-bottom:8px">
                    <div class="col-sm-1 col-md-1 col-lg-1" style=" padding-left: 0px; ">
                        <span><b>DE: </b></span>
                    </div>
                    <div class="col-sm-11 col-md-11 col-lg-11 " style="padding-left: 0px;">
                        <div   style="overflow:hidden;padding-left:5px;width:100%;height:25px;border:1px solid grey"   ><?php echo $fromname .' - '. $from   ; ?></div>
                    </div>
                </div>
                <div class="row" style="padding-bottom:8px">
                    <div class="col-sm-1 col-md-1 col-lg-1" style=" padding-left: 0px; ">
                        <span><b>A: </b></span>
                    </div>
                    <div class="col-sm-11 col-md-11 col-lg-11 " style="padding-left: 0px;">
                        <div   style="overflow:hidden;padding-left:5px;width:100%;height:45px;border:1px solid grey"   >
                         <?php   if(isset($to )) {
                            foreach ($to as $t) {
                           echo $t.' ; ';
                            }
                            }     ?>
                        </div>
                    </div>
                </div>
                <?php if($envoye['cc'] !='') {?>
                <div class="row" style="padding-bottom:8px">
                    <div class="col-sm-1 col-md-1 col-lg-1" style=" padding-left: 0px; ">
                        <span><b>CC: </b></span>
                    </div>
                    <div class="col-sm-11 col-md-11 col-lg-11 " style="padding-left: 0px;">
                        <div   style="overflow:hidden;padding-left:5px;width:100%;height:45px;border:1px solid grey"   >
                            <?php
                           echo $envoye['cc']; ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <div class="row" style="padding-bottom:8px">

                    <div class="col-sm-1 col-md-1 col-lg-1" style="padding-left: 0px;;">
                        <span><b> Sujet:</b></span>
                    </div>
                    <div class="col-sm-11 col-md-11 col-lg-11"style=" padding-left: 0px;color:black; ">
                        <div   style="overflow:hidden;padding-left:5px;width:100%;height:45px;border:1px solid grey"   ><?php echo $envoye['sujet'];  ?></div>
                    </div>
                </div>

            </div>
        </div>

        <div style="overflow:hidden;border:1px solid #d6eef7; padding:20px 20px 20px 20px; min-height: 400px!important;"> <?php  $content= $envoye['contenu'] ;
            echo $content ;       ?>
        </div>

    </div>

    <?php use App\Attachement ;?>

    @if ($envoye['nb_attach']  > 0)
        <?php
        echo '<br>Attachements :<br>';


      //  $attachs = Attachement::get()->where('parent', '=', $envoye['id'] )->where('boite', '=', 1 );

        $envid=$envoye['id'];
        $attachs = Attachement::where(function ($query)  use ($envid) {
            $query->where('envoye_id',$envid )
                ->where('boite',  1 );
        })->orWhere(function ($query) use ($envid )   {
            $query->where('parent', $envid )
                ->where('boite',  1 );
        })->get();

        ?>
    @endif

    @if (!empty($attachs) )
        <?php $i=1; ?>
        @foreach ($attachs as $att)
            <div class="tab-pane fade in" id="pj<?php echo $i; ?>">

                <h4><b style="font-size: 13px;">{{ $att->nom }}</b> (<a   style="font-size: 13px;" href="{{ URL::asset('storage'.$att->path) }}" download="{{ URL::asset('storage'.$att->path) }}">Télécharger</a>)</h4>

            </div>
        @endforeach
    @endif
    <div class="col-lg-2 ">
    </div>





</div>

</body>
  