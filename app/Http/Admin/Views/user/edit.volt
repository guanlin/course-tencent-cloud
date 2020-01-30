<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.user.update','id':user.id}) }}">

    <fieldset class="layui-elem-field layui-field-title">
        <legend>编辑用户</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">用户名</label>
        <div class="layui-input-block">
            <div class="layui-form-mid layui-word-aux">{{ user.name }}</div>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">头衔</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="title" value="{{ user.title }}">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">简介</label>
        <div class="layui-input-block">
            <textarea class="layui-textarea" name="about">{{ user.about }}</textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">教学角色</label>
        <div class="layui-input-block">
            <input type="radio" name="edu_role" value="1" title="学员" {% if user.edu_role == 1 %}checked{% endif %}>
            <input type="radio" name="edu_role" value="2" title="讲师" {% if user.edu_role == 2 %}checked{% endif %}>
        </div>
    </div>

    {% if auth_user.admin == 1 %}
        <div class="layui-form-item">
            <label class="layui-form-label">后台角色</label>
            <div class="layui-input-block">
                <input type="radio" name="admin_role" value="0" title="无" {% if user.admin_role == 0 %}checked{% endif %}>
                {% for item in roles %}
                    <input type="radio" name="admin_role" value="{{ item.id }}" title="{{ item.name }}" {% if user.admin_role == item.id %}checked{% endif %}>
                {% endfor %}
            </div>
        </div>
    {% endif %}

    <div class="layui-form-item">
        <label class="layui-form-label">锁定帐号</label>
        <div class="layui-input-block">
            <input type="radio" name="locked" value="1" title="是" lay-filter="locked" {% if user.locked == 1 %}checked="true"{% endif %}>
            <input type="radio" name="locked" value="0" title="否" lay-filter="locked" {% if user.locked == 0 %}checked="true"{% endif %}>
        </div>
    </div>

    <div class="layui-form-item" id="lock-expiry-block" {% if user.locked == 0 %}style="display:none;"{% endif %}>
        <label class="layui-form-label">锁定期限</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="lock_expiry" autocomplete="off" value="{{ date('Y-m-d H:i:s',user.lock_expiry) }}">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
        </div>
    </div>

</form>

<script>

    layui.use(['jquery', 'form', 'laydate'], function () {

        var $ = layui.jquery;
        var form = layui.form;
        var laydate = layui.laydate;

        laydate.render({
            elem: 'input[name=lock_expiry]',
            type: 'datetime'
        });

        form.on('radio(locked)', function (data) {
            var block = $('#lock-expiry-block');
            if (data.value == 1) {
                block.show();
            } else {
                block.hide();
            }
        });

    });

</script>