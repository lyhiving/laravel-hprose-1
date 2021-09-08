<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>whereof-laravel-hpose</title>
    <!-- 引入 layui.css -->
    <link rel="stylesheet" href="//unpkg.com/layui@2.6.8/dist/css/layui.css">

    <!-- 引入 layui.js -->
    <script src="//unpkg.com/layui@2.6.8/dist/layui.js"></script>
</head>
<body>
<div id="app">
    <table class="layui-table">
        <colgroup>
            <col width="150">
            <col width="200">
            <col>
        </colgroup>
        <thead>
        <tr>
            <th>class</th>
            <th>alias</th>
            <th>method</th>
            <th>arguments</th>
            <th>使用</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($list as $info)
            <tr>
                <td>{{ $info['class'] }}</td>
                <td>{{ $info['alias'] }}</td>
                <td>{{ $info['method'] }}</td>
                <td>{{ implode(',',$info['args'] )}}</td>
                <td>
                    @if(empty($info['class']))
                        app('hprose.socket.client') ->{{ $info['alias'] }}({{ implode(',',$info['args'] )}})
                    @else
                        app('hprose.socket.client')->{{ $info['alias'] }}
                        ->{{ $info['method'] }}
                        ({{ implode(',',$info['args'] )}})
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
