    [{if $oViewConf->getFbAppId()}]
    <div id="fb-root"></div>
    <script type="text/javascript">

        window.fbAsyncInit = function() {
            FB.init({appId: '[{$oViewConf->getFbAppId()}]', status: true, cookie: true, xfbml: true});

            FB.Event.subscribe('auth.login', function(response) {
                fbLogin();

                if ( FB.XFBML.Host.parseDomTree )
                      setTimeout( FB.XFBML.Host.parseDomTree, 0 );
            });

            FB.Event.subscribe('auth.logout', function(response) {
                fbLogout();
            });
        };

        (function() {
            var e = document.createElement('script');
            e.type = 'text/javascript';
            e.src = document.location.protocol + '//connect.facebook.net/[{ oxmultilang ident="FACEBOOK_LOCALE" }]/all.js';
            e.async = true;
            document.getElementById('fb-root').appendChild(e);
        }());

        function fbLogin() {
           sLoginUrl = document.location.pathname + addUrlParam(document.location.search, 'fblogin', '1');
           document.location.href = sLoginUrl;
        }

        function fbLogout() {
           sUrl = "[{ $oViewConf->getLogoutLink() }]";
           sUrl = sUrl.toString().replace(/&amp;/g,"&");

           document.location.href = sUrl;
        }

        function addUrlParam(search, key, val) {
          var newParam = key + '=' + val;
          var params   = '?' + newParam;

          // If the "search" string exists, then build params from it
          if (search) {
            // Try to replace an existance instance
            params = search.replace(new RegExp('[\?&]' + key + '[^&]*'), '$1' + newParam);

            // If nothing was replaced, then add the new param to the end
            if (params === search) {
              params += '&' + newParam;
            }
          }

          return params;
        };

    </script>
    [{/if}]
