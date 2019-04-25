<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Welcome to HarmonyCMS!</title>
    <style>
        body { background: #F5F5F5; font: 18px/1.5 sans-serif; }
        h1, h2 { line-height: 1.2; margin: 0 0 .5em; }
        h1 { font-size: 36px; }
        h2 { font-size: 21px; margin-bottom: 1em; }
        p { margin: 0 0 1em 0; }
        a { color: #0000F0; }
        a:hover { text-decoration: none; }
        code { background: #F5F5F5; max-width: 100px; padding: 2px 6px; word-wrap: break-word; }
        #wrapper { background: #FFF; margin: 1em auto; max-width: 800px; width: 95%; }
        #container { padding: 2em; }
        #welcome, #status { margin-bottom: 2em; }
        #welcome h1 span { display: block; font-size: 75%; }
        #comment { font-size: 14px; text-align: center; color: #777777; background: #FEFFEA; padding: 10px; }
        #comment p { margin-bottom: 0; }
        #icon-status, #icon-book { float: left; height: 64px; margin-right: 1em; margin-top: -4px; width: 64px; }
        #icon-book { display: none; }

        @media (min-width: 768px) {
            #wrapper { width: 80%; margin: 2em auto; }
            #icon-book { display: inline-block; }
            #status a { display: block; }

            @-webkit-keyframes fade-in { 0% { opacity: 0; } 100% { opacity: 1; } }
            @keyframes fade-in { 0% { opacity: 0; } 100% { opacity: 1; } }
            .sf-toolbar { opacity: 0; -webkit-animation: fade-in 1s .2s forwards; animation: fade-in 1s .2s forwards;}
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <div id="container">
            <div id="welcome">
                <h1><span>Welcome to</span> HarmonyCMS <?php echo $version; ?></h1>
            </div>

            <div id="status">
                <p>
                    <svg id="icon-status" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="isolation:isolate" viewBox="0 0 64 64" width="64" height="64"><defs><clipPath id="_clipPath_NQKiLFcDfEychLpWd4KvkKcdIyCcvFVY"><rect width="64" height="64"/></clipPath></defs><g clip-path="url(#_clipPath_NQKiLFcDfEychLpWd4KvkKcdIyCcvFVY)"><clipPath id="_clipPath_AaCk4VCSLqipsl84YGIUmBbTB5GIyJqy"><rect x="0" y="0" width="64" height="64" transform="matrix(1,0,0,1,0,0)" fill="rgb(255,255,255)"/></clipPath><g clip-path="url(#_clipPath_AaCk4VCSLqipsl84YGIUmBbTB5GIyJqy)"><g><path d=" M 30.791 31.04 L 18.489 18.695 L 37.113 0 L 49.436 12.352 L 30.791 31.04 Z  M 51.684 49.223 L 64 36.878 L 45.511 18.389 L 33.209 30.699 L 51.684 49.223 Z  M 14.706 51.648 L 27.022 64 L 45.696 45.305 L 33.38 32.96 L 14.706 51.648 Z  M 12.288 14.599 L 0 26.951 L 18.645 45.646 L 30.962 33.301 L 12.288 14.599 Z " fill="rgb(46,161,248)"/></g></g></g></svg>

                    Your application is now ready. You can start working on it at:<br>
                    <code><?php echo $baseDir; ?></code>
                </p>
            </div>

            <div id="next">
                <h2>What's next?</h2>
                <p>
                    <svg id="icon-book" xmlns="http://www.w3.org/2000/svg" style="isolation:isolate" viewBox="0 0 64 64" width="64"
                         height="64">
                      <defs>
                        <clipPath id="_clipPath_sijxoFzd6c8NQjN8SB3VbQj7AQZ5PXNv">
                          <rect width="64" height="64"/>
                        </clipPath>
                      </defs>
                      <g clip-path="url(#_clipPath_sijxoFzd6c8NQjN8SB3VbQj7AQZ5PXNv)">
                        <path
                            d=" M 61 45 L 61 3 C 61 1.338 59.663 0 58 0 L 17 0 C 10.375 0 5 5.375 5 12 L 5 52 C 5 58.625 10.375 64 17 64 L 58 64 C 59.663 64 61 62.663 61 61 L 61 59 C 61 58.063 60.563 57.213 59.888 56.663 C 59.363 54.738 59.363 49.25 59.888 47.325 C 60.563 46.788 61 45.938 61 45 Z  M 21 16.75 C 21 16.337 21.338 16 21.75 16 L 48.25 16 C 48.663 16 49 16.337 49 16.75 L 49 19.25 C 49 19.663 48.663 20 48.25 20 L 21.75 20 C 21.338 20 21 19.663 21 19.25 L 21 16.75 Z  M 21 24.75 C 21 24.338 21.338 24 21.75 24 L 48.25 24 C 48.663 24 49 24.338 49 24.75 L 49 27.25 C 49 27.663 48.663 28 48.25 28 L 21.75 28 C 21.338 28 21 27.663 21 27.25 L 21 24.75 Z  M 52.675 56 L 17 56 C 14.788 56 13 54.213 13 52 C 13 49.8 14.8 48 17 48 L 52.675 48 C 52.438 50.138 52.438 53.863 52.675 56 Z "
                            fill="rgb(170,170,170)"/>
                      </g>
                    </svg>

                    Read the <a href="https://dev-docs.harmonycms.net" target="_blank">Harmony Developer Documentation</a>
                    <br>to start building your website.
                </p>
            </div>
        </div>
        <div id="comment">
            <p>
                You're seeing this page because debug mode is enabled and you haven't active a default theme yet.
            </p>
        </div>
    </div>
</body>
</html>
