{% extends "layouts/master.volt" %}

{% block content %}
	<div class="container">
		<div class="panel panel-default">
			<div class="panel-heading">Thông tin hình ảnh #{{ image.id }}</div>

			<div class="panel-body">
				<div class="text-center">
					<img src="{{ image.getUrl() }}" />
				</div>

				<div class="row">
					<div class="col-md-6 col-md-offset-3" style="margin-top: 20px;">
						<div class="form-horizontal">
							<div class="form-group">
								<div class="input-group">
									<div class="input-group-addon">Ảnh gốc</div>

									<input class="form-control" type="email" value="{{ image.getUrl() }}" />
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
{% endblock %}

{% block javascript %}
	{{ super() }}

	<script type="text/javascript">
		$(document).ready(function(){
			$("input").on('click', function(){
				$(this).select();
			});
		});
	</script>
{% endblock %}