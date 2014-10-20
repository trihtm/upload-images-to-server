<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Upanh - Clan.Vn</title>

        {{ stylesheet_link("css/reset.css") }}
        {{ stylesheet_link("css/normalize.css") }}

        <!-- Bootstrap -->
        {{ stylesheet_link("css/bootstrap.min.css") }}
        {{ stylesheet_link("css/main.css") }}

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            {{ javascript_include("vendor/html5/html5shiv.js") }}
            {{ javascript_include("vendor/html5/respond.min.js") }}
        <![endif]-->
    </head>

    <body>
        <nav class="navbar navbar-default" role="navigation">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="{{ url('') }}">UPANH</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li {% if module == 'upload' %}class="active"{% endif %}>
                            <a href="{{ url('guest/default') }}">Up ảnh mới</a>
                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>

        {% block content %}{% endblock %}

        {% block javascript %}
            <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
            {{ javascript_include("js/jquery-1.10.2.min.js") }}
            {{ javascript_include("js/jquery-migrate-1.2.1.min.js") }}

            <!-- Include all compiled plugins (below), or include individual files as needed -->
            {{ javascript_include("js/bootstrap.min.js") }}
        {% endblock %}
    </body>
</html>