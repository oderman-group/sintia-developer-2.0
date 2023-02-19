<!-- Global site tag (gtag.js) - Google Analytics -->
<?php
switch($_SERVER['HTTP_HOST']){
	case 'localhost':
    echo "
    <script async src='https://www.googletagmanager.com/gtag/js?id=UA-52643833-14'></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-52643833-14');
    </script>
    ";
    break;

	case 'developer.plataformasintia.com':
    echo "
    <script async src='https://www.googletagmanager.com/gtag/js?id=UA-52643833-14'></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-52643833-14');
    </script>
    ";
	  break;

	case 'main.plataformasintia.com':
    echo "
    <script async src='https://www.googletagmanager.com/gtag/js?id=G-H2QYYN10VP'></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-H2QYYN10VP');
    </script>
    ";
    break;
}
