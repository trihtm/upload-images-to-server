{% extends "layouts/master.volt" %}

{% block content %}
	{{ flash.output() }}

	<div class="container">
        <div class="sidebar">
            <div class="panel panel-default">
                <div class="panel-heading">Danh sách Folder</div>

                <div class="list-group">
                	{% for stackFolder in folders %}
                    	<a href="{{ url('guest/default/') }}{{ stackFolder.id }}" class="list-group-item {% if folder_id == stackFolder.id %}active{% endif %}">
                    		{{ stackFolder.name }}
                    	</a>
                    {% endfor %}
                </div>
            </div>
        </div>

        <div class="rightbar">
            <div class="panel panel-default">
                <div class="panel-heading">Danh sách ảnh trong folder <b>{{ folder.name }}</b></div>

                <div class="panel-body">
    				<form role="form" action="{{ url('guest/default') }}/{{ folder.id }}" method="POST" enctype="multipart/form-data" class="form-inline">
    					<div class="form-group">
    						<label for="FileInput">Tải file mới</label>

    						<input type="file" id="FileInput" name="FileInput" />

    						<p class="help-block">Hỗ trợ định dạng jpg, jpeg, png, gif</p>
    					</div>

    					<button type="submit" class="btn btn-danger">Xác nhận</button>
    				</form>

                    <hr />

                    <div class="list-photos">
                        {% for image in images %}
                            <div class="photo">
                                <a href="{{ url('guest/show') }}/{{ image.id }}">
                                    <img src="{{ image.getUrl() }}" />
                                </a>
                            </div>
                        {% endfor %}
                    </div>
                </div>
            </div>
        </div>
    </div>
{% endblock %}