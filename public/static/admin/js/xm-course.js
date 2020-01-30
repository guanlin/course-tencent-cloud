/**
 * 挑选课程组件
 * @param data array 默认数据
 * @param url string 请求地址
 */
function xmCourse(data, url) {

    layui.use(['jquery', 'table'], function () {

        var $ = layui.jquery;
        var table = layui.table;

        var xmCourse = xmSelect.render({
            el: '#xm-course-ids',
            name: 'xm_course_ids',
            height: 'auto',
            autoRow: true,
            prop: {
                name: 'title',
                value: 'id',
            },
            data: data,
            content: `
            <div class="kg-search-box">
                <div class="layui-inline">
                    <input class="layui-input" type="text" placeholder="请输入课程标题..." id="search-keyword">
                </div>
                <div class="layui-inline">
                    <button type="button" class="layui-btn" id="search-btn">搜索</button>
                </div>
            </div>
            <table class="layui-hide" id="course-table" lay-filter="course"></table>`
        });

        table.render({
            id: 'course-table',
            elem: '#course-table',
            url: url,
            page: true,
            cols: [[
                {field: 'id', title: '编号', width: 50},
                {field: 'title', title: '标题', width: 300},
                {
                    field: 'model', title: '类型', width: 50, templet: function (d) {
                        if (d.model === 'vod') {
                            return '<span class="layui-badge layui-bg-green">点播</span>';
                        } else if (d.model === 'live') {
                            return '<span class="layui-badge layui-bg-orange">直播</span>';
                        } else if (d.model === 'read') {
                            return '<span class="layui-badge layui-bg-black">图文</span>';
                        }
                    }
                },
                {
                    field: 'lesson_count', title: '学员数', width: 55, templet: function (d) {
                        return '<span class="layui-badge layui-bg-gray">' + d.user_count + '</span>';
                    }
                },
                {
                    field: 'lesson_count', title: '课时数', width: 55, templet: function (d) {
                        return '<span class="layui-badge layui-bg-gray">' + d.lesson_count + '</span>';
                    }
                },
                {
                    field: 'market_price', title: '市场价', width: 60, templet: function (d) {
                        return '￥' + d.market_price;
                    }
                },
                {
                    field: 'vip_price', title: '会员价', width: 60, templet: function (d) {
                        return '￥' + d.vip_price;
                    }
                },
            ]]
        });

        table.on('rowDouble(course)', function (obj) {
            var item = obj.data;
            var values = xmCourse.getValue();
            var has = values.find(function (i) {
                return i.id === item.id;
            });
            if (!has) {
                xmCourse.append([item]);
            }
        });

        $('#search-btn').on('click', function () {
            table.reload('course-table', {
                where: {title: $('#search-keyword').val()},
                page: {curr: 1}
            });
        });

    });

}