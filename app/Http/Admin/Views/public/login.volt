<form class="kg-login-form layui-form" method="POST" action="{{ url({'for':'admin.login'}) }}">

    <fieldset class="layui-elem-field layui-field-title">
        <legend>后台登录</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label"><i class="layui-icon layui-icon-username"></i></label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="account" autocomplete="off" placeholder="手机/邮箱" lay-verify="required">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label"><i class="layui-icon layui-icon-password"></i></label>
        <div class="layui-input-block">
            <input class="layui-input" type="password" name="password" autocomplete="off" placeholder="密码" lay-verify="required">
        </div>
    </div>

    {% if captcha.enabled == 1 %}
        <div class="layui-form-item">
            <label class="layui-form-label"><i class="layui-icon layui-icon-vercode"></i></label>
            <div class="layui-input-block">
                <span id="captcha-btn" class="layui-btn layui-btn-primary layui-btn-fluid" app-id="{{ captcha.app_id }}">点击完成验证</span>
                <span id="verify-tips" class="kg-btn-verify layui-btn layui-btn-primary layui-btn-disabled layui-btn-fluid layui-hide"><i class="layui-icon layui-icon-ok"></i>验证成功</span>
            </div>
        </div>
    {% endif %}

    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button id="submit-btn" class="layui-btn layui-btn-fluid" {% if captcha.enabled %}disabled="disabled"{% endif %} lay-submit="true" lay-filter="go">登录</button>
            <input type="hidden" name="ticket">
            <input type="hidden" name="rand">
        </div>
    </div>

</form>

<script>
    if (window != top) {
        top.location.href = window.location.href;
    }
</script>

{% if captcha.enabled == 1 %}

    <script src="https://ssl.captcha.qq.com/TCaptcha.js"></script>

    <script>

        layui.use(['jquery', 'form'], function () {
            var $ = layui.jquery;
            var captcha = new TencentCaptcha(
                $('#captcha-btn')[0],
                $('#captcha-btn').attr('app-id'),
                function (res) {
                    $('input[name=ticket]').val(res.ticket);
                    $('input[name=rand]').val(res.randstr);
                    $('#captcha-btn').remove();
                    $('#submit-btn').removeAttr('disabled');
                    $('#verify-tips').removeClass('layui-hide');
                }
            );
        });

    </script>

{% endif %}